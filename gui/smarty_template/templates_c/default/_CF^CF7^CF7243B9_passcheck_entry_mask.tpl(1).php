<?php /* Smarty version 2.6.22, created on 2020-12-28 11:47:52
         compiled from main/passcheck_entry_mask.tpl */ ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><?php echo $this->_tpl_vars['companyName']; ?>
</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li><?php echo $this->_tpl_vars['companyAddress']; ?>
</li>
    </ul>
  </div>
</nav>

<div class="container-fluid d-flex justify-content-center">
    <div class="row col-md-4">
        <div class="card ">
            <div class="card-header bg-info" align="center">
                CareMD Version: 4.0
            </div>
            <div class="card-body">

                <?php if ($this->_tpl_vars['bShowErrorPrompt']): ?>
                    <table border=0>
                        <tr>
                            <td><?php echo $this->_tpl_vars['sMascotImg']; ?>
</td>
                            <td align="center"><?php echo $this->_tpl_vars['sErrorMsg']; ?>
</td>
                        </tr>
                    </table>
                <?php endif; ?>


                <form <?php echo $this->_tpl_vars['sPassFormParams']; ?>
>
                    <div class="form-group">
                        <label class="">
                            <?php echo $this->_tpl_vars['LDUserPrompt']; ?>
:
                        </label>
                            <input class="form-control" type="text" name="userid" size="14" maxlength="25">
                    </div>
                    <div class="form-group">
                        <label class="">
                            <?php echo $this->_tpl_vars['LDPwPrompt']; ?>
:
                        </label>
                            <input class="form-control" name="keyword" type="password">
                        
                    </div>
                   
                     <?php echo $this->_tpl_vars['sPassHiddenInputs']; ?>


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

