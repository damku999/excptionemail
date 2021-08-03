<!DOCTYPE html>
<html>
    <head>
        <meta name="robots" content="noindex,nofollow" />
        <style>
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
            .extra-info {
                background-color: #FFFFFF;
                padding: 15px 28px;
                margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
            {!! $css !!}
            .exception-summary {
                background-color:#000 !important;
            }
            .exception-http{
                color:#FFFFFF !important;
            }
            .exception-http h2 small{
                color:#FFFFFF !important;
            }
             .container h2 small{
                color:#FFFFFF !important;
            }
            .container h2 {
                color:#FFFFFF !important;
            }
            .container h2 a abbr{
                color:#FFFFFF !important;
            }
        </style>
    </head>
    <body>
        <div class="extra-info">
            Requested Url - {{ request()->url() }}
        </div>
         <div class="extra-info">
            Request Headers - <pre>{{ print_r(request()->header(), true) }}</pre>
        </div>
        <div class="extra-info">
            Request Body - <pre>{{ print_r(request()->all() ?? request()->json()->all(), true) }}</pre>
        </div> 
        <div class="extra-info">
            &#128336; &nbsp;{{ date('l, jS \of F Y h:i:s a') }} {{ date_default_timezone_get() }}
        </div>
        <div class="extra-info">
            User - {{ optional(request()->user())->email ?? 'Guest' }}
        </div>
        <div class="extra-info">
            User - {{ request()->ip() ?? '' }}
        </div>
        <div class="extra-info">
            {!! $content !!}
        </div>
    </body>
</html>
