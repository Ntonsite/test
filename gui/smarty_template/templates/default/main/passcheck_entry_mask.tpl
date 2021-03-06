<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">{{$companyName}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li>{{$companyAddress}}</li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <img src='http://18.219.56.242/gui/smarty_template/templates/default/main/gif/web.gif'  height="750px" alt="Quality Pic presenting">
        </div>
        <div class="col-md-4">
            <div class="card ">
                <div class="card-header bg-info" align="center">
                    CareMD Version: 4.0
                </div>
                <div class="card-body">

                    {{if $bShowErrorPrompt}}
                    <table border=0>
                        <tr>
                            <td>{{$sMascotImg}}</td>
                            <td align="center">{{$sErrorMsg}}</td>
                        </tr>
                    </table>
                    {{/if}}


                    <form {{$sPassFormParams}}>
                        <div class="form-group">
                            <label class="">
                                {{$LDUserPrompt}}:
                            </label>
                            <input class="form-control" type="text" name="userid" size="14" maxlength="25">
                        </div>
                        <div class="form-group">
                            <label class="">
                                {{$LDPwPrompt}}:
                            </label>
                            <input class="form-control" name="keyword" type="password">

                        </div>

                        {{$sPassHiddenInputs}}

                        <div class="form-group">
                            <div class="col-md-8">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-btn fa-sign-in">
                                    </i>
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


