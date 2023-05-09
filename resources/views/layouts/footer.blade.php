<footer class="footer">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                @yield('copyright') &copy; Copyright {{$web_config['webSitetitle']}} <script>document.write(new Date().getFullYear())</script>.
            </div>
            <div class="col-sm-6 float-right">
 <!--
            
            stuff here -->
                {!!$web_config['webSlogan']!!}
                <img src="{{ URL::asset('/assets/images/hummingbird_SM.png')}}"  width='40' alt="Hummingbird">
                
            </div>
        </div>
    </div>
</footer>
