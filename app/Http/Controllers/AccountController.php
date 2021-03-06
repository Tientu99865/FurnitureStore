<?php

namespace App\Http\Controllers;

use App\Customers;
use App\Http\Requests\RequestResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    //
    public function getIndex(){
        return view('pages.tai-khoan.index');
    }

    public function postRegister(Request $request){
        $this->validate($request,
            [
                'name'=>'required|min:3',
                'email'=>'required|email|unique:customers',
                'phone_number'=>'required|numeric',
                'address'=>'required',
                'password'=>'required|min:6|max:32',
                're_password'=>'required|same:password'
            ],
            [
                'name.required'=>'Bạn chưa nhập họ tên của bạn',
                'name.min'=>'Họ tên của bạn phải nhập lớn hơn 3 ký tự',
                'email.required'=>'Bạn chưa nhập email',
                'email.email'=>'Bạn chưa nhập đúng định dạng của email',
                'email.unique'=>'Email đã tồn tại',
                'phone_number.required'=>'Bạn chưa nhập số điện thoại',
                'phone_number.numeric'=>'Số điện thoại phải là kiểu số',
                'address.required'=>'Bạn chưa nhập địa chỉ',
                'password.required'=>'Bạn chưa nhập mật khẩu',
                'password.min'=>'Mật khẩu không thể nhỏ hơn 6 ký tự',
                'password.max'=>'Mật khẩu không thể lớn hơn 32 ký tự',
                're_password.required'=>'Bạn chưa nhập lại mật khẩu',
                're_password.same'=>'Mật khẩu không trùng nhau'
            ]);
        $customer = new Customers();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;
        $customer->password = bcrypt($request->password);
        $customer->active = false;
        $customer->save();
        if ($customer->id){
            $email = $customer->email;
            $code = bcrypt(md5(time().$email));
            $url = route('customer.verify.account',['id' => $customer->id,'code'=>$code]);

            $customer->code_active = $code;
            $customer->time_active = Carbon::now();
            $customer->save();

            $data = [
                'route' => $url
            ];

            Mail::send('email.verify_account',$data,function ($message) use ($email){
                $message->to($email,'Xác nhận tài khoản')->subject('Xác nhận tài khoản');
            });
        }


        return redirect('/')->with('ThongBao','Bạn đã đăng ký thành công ,Vui lòng kiểm tra email để xác nhận tài khoản!');
    }

    public function postLogin(Request $request){
        $this->validate($request,
            [
                'email'=>'required|email',
                'password'=>'required|min:6|max:32'
            ],
            [
                'email.required'=>'Bạn chưa nhập email',
                'email.email'=>'Bạn chưa nhập đúng định dạng của email',
                'password.required'=>'Bạn chưa nhập mật khẩu',
                'password.min'=>'Mật khẩu không thể nhỏ hơn 6 ký tự',
                'password.max'=>'Mật khẩu không thể lớn hơn 32 ký tự'
            ]);
        $data = $request->only('email','password');
        if (Auth::guard('customers')->attempt($data)){
            $customer = Auth::guard('customers')->user();
            if ($customer['active'] == 1){
                return redirect('/')->with('ThongBao','Bạn đã đăng nhập thành công!');
            }else{
                return redirect('/')->with('Loi','Bạn chưa xác nhận tài khoản ! Vui lòng kiểm tra email và xác nhận tài khoản ');
            }
        }
        else
        {
            return redirect('tai-khoan/index')->with('LoiDangNhap','Đăng nhập không thành công');
        }
    }


    public function getLogout(){
        Auth::guard('customers')->logout();

        return redirect('/')->with('ThongBao','Bạn đã đăng xuất thành công');
    }

    public function verifyAccount(Request $request){
        $code = $request->code;
        $id = $request->id;

        $checkCustomer = Customers::where([
           'code_active' => $code,
            'id' => $id
        ])->first();

        if (!$checkCustomer){
            return redirect('/')->with('Loi','Xin lỗi ! Đường dẫn xác nhận tài khoản không tồn tại');
        }

        $checkCustomer->active = 1;
        $checkCustomer->save();

        return redirect('/')->with('ThongBao','Xác nhận tài khoản thành công');
    }

    public function getForgotPassword(){
        return view('pages/tai-khoan/quen-mat-khau');
    }

    public function sendCodeResetPassword(Request $request){
        $email = $request->email;
        $checkCustomer = Customers::where('email',$email)->first();

        if (!$checkCustomer){
            return redirect()->back()->with('Loi','Tài khoản không tồn tại');
        }

        $code = bcrypt(time().$email);

        $checkCustomer->code = $code;
        $checkCustomer->time_code = Carbon::now();
        $checkCustomer->save();

        $url = route('get.link.reset.password',['code'=>$checkCustomer->code,'email'=>$email]);
        $data = [
            'route' => $url
        ];
        Mail::send('email.reset_password',$data,function ($message) use ($email){
            $message->to($email,'Lấy lại mật khẩu')->subject('Lấy lại mật khẩu');
        });

        return redirect()->back()->with('ThongBao','Link lấy lại mật khẩu đã gửi vào email của bạn! Vui lòng kiếm tra email.');
    }

    public function resetPassword(Request $request){
        $code = $request->code;
        $email = $request->email;

        $checkCustomer = Customers::where([
           'code' => $code,
            'email' => $email
        ])->first();

        if (!$checkCustomer){
            return redirect('/')->with('Loi','Xin lỗi ! Đường dẫn lấy lại mật khẩu không đúng , vui lòng thử lại .');
        }

        return view('pages/tai-khoan/cai-lai-mat-khau');
    }

    public function saveResetPassword(RequestResetPassword $requestResetPassword){
        if ($requestResetPassword->password){
            $code = $requestResetPassword->code;
            $email = $requestResetPassword->email;

            $checkCustomer = Customers::where([
                'code' => $code,
                'email' => $email
            ])->first();

            if (!$checkCustomer){
                return redirect('/')->with('Loi','Xin lỗi ! Đường dẫn lấy lại mật khẩu không đúng , vui lòng thử lại .');
            }

            $checkCustomer->password = bcrypt($requestResetPassword->password);
            $checkCustomer->save();

            return redirect('/')->with('ThongBao','Mật khẩu đã được thay đổi thành công');
        }
    }
}
