{{* chemlab_data_results.tpl *}}
<table width="100%" border="0">
    <tbody>
        <tr valign="top">
            <td>
                {{* table block for the patient basic data *}}
                <form method="post" {{$sFormAction}} onSubmit="return pruf(this)" name="datain">
                    <table>
                        <tbody>
                            <tr>

                                <td class="adm_item">{{$LDCaseNr}}:</td>
                                <td class="adm_input">{{$sPID}}</td>
                            </tr>
                            <tr>
                                <td class="adm_item">{{$LDLastName}}, {{$LDName}}:</td>
                                <td class="adm_input"><b>{{$sLastName}}, {{$sName}}</b></td>
                            </tr>
                            <tr>
                                <td class="adm_item">{{$LDBday}}:</td>
                                <td class="adm_input"><b>{{$sBday}}</b></td>
                            </tr>
                            <tr>
                                <td class="adm_item">{{$LDJobIdNr}}:</td>
                                <td class="adm_input">{{$sJobIdNr}}</td>
                            </tr>
                            <tr>
                                <td class="adm_item">{{$LDExamDate}}:</td>
                                <td class="adm_input">{{$sExamDate}} {{$sMiniCalendar}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table width="100%"  bgcolor=#ffdddd  class="table table-condensed" style="position: relative;">
                        <tbody>                            
                            {{if $batchMismatch}}
                            <tr>
                                <td colspan="2">

                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <i class="material-icons">close</i>
                                        </button>
                                        <span>
                                          <b> Mismatch - </b> The file's batch number doesn't match patients batch number. Please try again.</span>
                                    </div>
                                </td>
                            </tr>
                            {{else}}
                        
                                {{if $uploadedFile}}
                                <tr>
                                    <td colspan="2">

                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <i class="material-icons">close</i>
                                            </button>
                                            <span>
                                              <b> Uploaded - </b> Patient file was successfully uploaded</span>
                                        </div>
                                    </td>
                                </tr>
                                {{else}}
                                 <tr>
                                    <td colspan="2" style="color: white; background-color: lightgreen; font-weight: bold;">{{$sParamGroup}}</td>
                                </tr>
                                {{/if}}
                            {{/if}}

                            <tr>
                                <td colspan="1">

                                    <style>
                                       .a10_b {
                                            font-size: 14px;
                                       }
                                        input[type="text"]{ padding: 0 auto; line-height: 22px; font-size: 14px; }
                                    </style>


                                    {{* Table block for the parameters *}}
                                    <table   class="table table-condensed table-bordered" >
                                        <tbody>
                                            {{* Rows of parameters *}}
                                            {{$sParameters}}
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td>{{$pbSave}} &nbsp;  &nbsp;   &nbsp;   {{$pbSaveDone}} {{$pbAccept}} {{$pbReject}}</td>
                                <td align="right">{{$pbShowReport}} {{$pbCancel}}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{$sSaveParamHiddenInputs}}
                </form>

                

                <div style="position: absolute; top:210px; right: 10%; ">

                     {{$resultFormUpload}}
                </div>


                {{* Block for parameter group select box *}}
                <form {{$sFormAction}} method=post onSubmit="return chkselect(this)" name="paramselect">
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="3"><b>{{$LDSelectParamGroup}}</b></td>
                            </tr>
                            <tr>
                                <td>{{$LDParamGroup}}</td>
                                <td>{{$sParamGroupSelect}}</td>
                                <td>{{$sSubmitSelect}}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{$sSelectGroupHiddenInputs}}
                </form>

            </td>
            <td width="20%">
                {{* Table block for the help links *}}
                <table class="submenu3_body">
                    <tbody>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDParamNoSee}}</td>
                        </tr>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDOnlyPair}}</td>
                        </tr>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDHow2Save}}</td>
                        </tr>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDWrongValueHow}}</td>
                        </tr>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDVal2Note}}</td>
                        </tr>
                        <tr>
                            <td>{{$sAskIcon}}</td>
                            <td>{{$LDImDone}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
