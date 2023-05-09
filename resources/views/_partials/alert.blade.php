@if (!empty($errors) && $errors->any())
    <div class="row alert-wrapper" id="alertmessage">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissable">
                <div class="alert-container">
                    <i class="fa fa-exclamation-triangle pr10"></i>
                    @if ($errors->count() == 1)
                        {{ $errors->first() }}
                    @else
                        Errors
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>
        </div>
    </div>
@elseif (session()->has('error'))
    <div class="row alert-wrapper"  id="alertmessage">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle pr10"></i>
                {!! session()->get('error') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
        </div>
    </div>
@elseif (session()->has('warning'))
    <div class="row alert-wrapper"  id="alertmessage">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissable">
                <div class="alert-container">
                    <i class="fas fa-exclamation-triangle pr10"></i>
                    {!! session('warning') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>
        </div>
    </div>
@elseif (session()->has('success'))
    <div class="row alert-wrapper"  id="alertmessage">
        <div class="col-12">
            <div class="alert alert-success alert-dismissable">
                <div class="alert-container">
                    <i class="fas fa-check-circle pr10"></i>
                    {!! session('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>
        </div>
    </div>
@elseif (session()->has('info'))
    <div class="row alert-wrapper"  style="position:absolute; top:160px; left:480px;z-index: 1000; height:400px; width:600px;" id="alertmessage">
        <div class="col-12">
            <div class="alert alert-info alert-dismissable">
                <div class="alert-container">
                    <i class="fa fa-info-circle pr10"></i>
                    {!! session('info') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>
        </div>
    </div>
@endif
