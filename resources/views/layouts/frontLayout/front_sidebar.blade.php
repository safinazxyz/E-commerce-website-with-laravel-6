<?php
use App\Product;
?>
<form action="{{ url('/products-filter') }}" method="post">{{ csrf_field() }}
    <input name="url" value="{{ $url }}" type="hidden" >
<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian">
        @foreach($categories as $cat)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordian" href="#{{$cat->id}}">
                            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                            {{$cat->name}}
                        </a>
                    </h4>
                </div>
                <div id="{{$cat->id}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul>
                            @foreach($cat->categories as $subcat)
                                <?php $productCount = Product::productCount($subcat->id); ?>
                                <li>
                                    <a href="{{ asset('/products/'.$subcat->url) }}">{{$subcat->name}} </a>
                                    ({{ $productCount }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <h2>Colors</h2>
    <div class="panel-group category-products">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a style="vertical-align: middle;margin-top: 0;">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="black" value="black" type="checkbox"style="vertical-align: middle;margin-top: 0;"><span
                            class="badge pull-right"></span>
                        Black
                    </a>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a style="vertical-align: middle;margin-top: 0;">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="blue" value="blue" type="checkbox" style="vertical-align: middle;margin-top: 0;"><span
                            class="badge pull-right"></span>
                        Blue
                    </a>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a style="vertical-align: middle;margin-top: 0;">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="red" value="red" type="checkbox" style="vertical-align: middle;margin-top: 0;"><span
                            class="badge pull-right"></span>
                        Red
                    </a>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a style="vertical-align: middle;margin-top: 0;">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="green" value="green" type="checkbox" style="vertical-align: middle;margin-top: 0;"><span
                            class="badge pull-right"></span>
                        Green
                    </a>
                </h4>
            </div>
        </div>
    </div>
</div>
</form>
