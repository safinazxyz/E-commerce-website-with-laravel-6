<?php $url = url()->current(); ?>
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li <?php if(preg_match("/dashboard/i",$url)) { ?> class="active" <?php } ?>>
            <a href="{{ url('/admin/dashboard') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/categor/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li<?php if(preg_match("/add-category/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-category')}}">Add Category</a></li>
                <li<?php if(preg_match("/view-categories/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-categories')}}">View Categories</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/product/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/add-product/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-product')}}">Add Product</a></li>
                <li<?php if(preg_match("/view-products/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-products')}}">View Products</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/coupon/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/add-coupon/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-coupon')}}">Add Coupon</a></li>
                <li <?php if(preg_match("/view-coupons/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-coupons')}}">View Coupons</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Orders</span> <span class="label label-important">1</span></a>
            <ul <?php if(preg_match("/order/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/view-orders/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-orders')}}">View Orders</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Banners</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/banner/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/add-banner/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-banner')}}">Add Banner</a></li>
                <li <?php if(preg_match("/view-banners/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-banners')}}">View Banners</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Users</span> <span class="label label-important">1</span></a>
            <ul <?php if(preg_match("/user/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/view-users/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-users')}}">View Users</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Cms Pages</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/cms-page/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/add-cms-page/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-cms-page')}}">Add Cms Page</a></li>
                <li <?php if(preg_match("/view-cms-pages/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-cms-pages')}}">View Cms Pages</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Currencies</span> <span class="label label-important">2</span></a>
            <ul <?php if(preg_match("/currencies/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/add-currency/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/add-currency')}}">Add Curreny</a></li>
                <li <?php if(preg_match("/view-currencies/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-currencies')}}">View Currencies</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Enquiries</span> <span class="label label-important">1</span></a>
            <ul <?php if(preg_match("/enquiries/i",$url)) { ?> style="display: block;" <?php } ?>>
                <li <?php if(preg_match("/view-enquiries/i",$url)) { ?> class="active" <?php } ?>><a href="{{url('/admin/view-enquiries')}}">View Enquiries</a></li>
            </ul>
        </li>
        <?php /*
        //Removed unwanted tabs.
        <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
        <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>
        <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>
        <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="label label-important">3</span></a>
            <ul>
                <li><a href="form-common.html">Basic Form</a></li>
                <li><a href="form-validation.html">Form with Validation</a></li>
                <li><a href="form-wizard.html">Form with Wizard</a></li>
            </ul>
        </li>
        <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
        <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>
        <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label label-important">5</span></a>
            <ul>
                <li><a href="index2.html">Dashboard2</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="calendar.html">Calendar</a></li>
                <li><a href="invoice.html">Invoice</a></li>
                <li><a href="chat.html">Chat option</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
            <ul>
                <li><a href="error403.html">Error 403</a></li>
                <li><a href="error404.html">Error 404</a></li>
                <li><a href="error405.html">Error 405</a></li>
                <li><a href="error500.html">Error 500</a></li>
            </ul>
        </li>
        <li class="content"> <span>Monthly Bandwidth Transfer</span>
            <div class="progress progress-mini progress-danger active progress-striped">
                <div style="width: 77%;" class="bar"></div>
            </div>
            <span class="percent">77%</span>
            <div class="stat">21419.94 / 14000 MB</div>
        </li>
        <li class="content"> <span>Disk Space Usage</span>
            <div class="progress progress-mini active progress-striped">
                <div style="width: 87%;" class="bar"></div>
            </div>
            <span class="percent">87%</span>
            <div class="stat">604.44 / 4000 MB</div>
        </li>*/?>
    </ul>
</div>
<!--sidebar-menu-->
