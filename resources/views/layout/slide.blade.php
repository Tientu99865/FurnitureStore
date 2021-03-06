<section>
    <div class="rev_slider_wrapper fullscreen-container">
        <div class="rev-slider fullscreenbanner" id="slide-1" data-version="5.0">
            <ul>
                @foreach($posts as $post)
                    <li data-transition="fade">
                        <img src="upload/slide/{{$post->slide->image}}" alt="{{$post->slide->name}}" class="rev-slidebg">
                        <div class="tp-caption slider-heading-1" data-x="left" data-y="center" data-transform_in="y:-80px;opacity:0;s:800;e:easeInOutCubic;" data-transform_out="y:-80px;opacity:0;s:300;" data-voffset="[-35,-35,-75,-70]" data-fontsize="['70','60','55','42']" data-hoffset="['60','100','50','20']" data-start="900">
                            {{$post->title}}
                        </div>
                        <div class="tp-caption slider-heading-2" data-x="left" data-y="center" data-transform_in="x:80px;opacity:0;s:800;e:easeInOutCubic;" data-transform_out="x:80px;opacity:0;s:300;" data-whitespace="normal" data-width="['900','900','900','700','520'" data-voffset="[45,45,10,10,-15,-10]" data-fontsize="['25','25','25','21']" data-hoffset="['60','100','50','20']" data-start="1100">
                            {!! $post->short_description !!}
                        </div>
                        <div class="tp-caption" data-x="left" data-y="center" data-transform_in="y:80px;opacity:0;s:800;e:easeInOutCubic;" data-transform_out="y:80px;opacity:0;s:300;" data-voffset="[160,160,105,105,65,70]" data-hoffset="['60','100','50','20']" data-start="1100">
                            <a href="/tin-tuc/{{$post->id}}" class="btn btn-color text-uppercase">Xem thêm</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
