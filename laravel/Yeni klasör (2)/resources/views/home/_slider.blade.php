<div class="header_slide">
    <div class="header_bottom_left">
        <div class="categories">
            @php
                $parentCategories=\App\Http\Controllers\HomeController::categoryList();
            @endphp
            <ul>
                <h3>Kategoriler</h3>
                @foreach($parentCategories as $rs)
                    <li><a href="{{route('categoryproducts',['id'=>$rs->id,'slug'=>$rs->title])}}">{{$rs->title}}</a></li>
                @endforeach

            </ul>
        </div>
    </div>
    <div class="header_bottom_right">
        <div class="slider">
            <div id="slider">
                <div id="mover">
                    @foreach($slider->take(3) as $rs)
                        <div id="slide-1" class="slide">
                            <div class="slider-img">
                                <a href="{{route('product',['id' => $rs->id,'slug' => $rs->slug])}}"><img src="{{ Storage::url($rs->image) }}" style="width: 600px;" alt="learn more" /></a>
                            </div>
                            <div class="slider-text">
                                <h1>{{$rs->title}}</h1>
                                <h2>{{$rs->price}} ₺</h2>
                                <div class="features_list">
                                    <h4>{{ \App\Http\Controllers\Admin\CategoryController::getParentsTree($rs->category,$rs->category->title) }}</h4>
                                </div>
                                <a href="{{route('product',['id' => $rs->id,'slug' => $rs->slug])}}" class="button">Satın Al</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>


</div>



