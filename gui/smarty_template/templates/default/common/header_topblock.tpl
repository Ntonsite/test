{{* Toolbar - Topblock  *}}
<table cellspacing="0"  class="titlebar" border=0>
    <tr valign=top  class="titlebar" >
        <td bgcolor="{{$top_bgcolor}}" >
            &nbsp;{{$sTitleImage}}&nbsp;<font color="{{$top_txtcolor}}">{{$sToolbarTitle}}</font>
            {{if $Subtitle}}
            - {{$Subtitle}}
            {{/if}}
        </td>

        <td bgcolor="{{$top_bgcolor}}" >
            &nbsp;{{$sTitleImage}}&nbsp;<font color="{{$top_txtcolor}}">{{$sHealthFund}}</font>
            {{if $Subtitle}}
            - {{$Subtitle}}
            {{/if}}
        </td>
        <td bgcolor="{{$top_bgcolor}}" align=right>{{if !$disableButton}}{{if $pbAux2}}<a
                href="{{$pbAux2}}"><img {{$gifAux2}} alt="" {{$dhtml}} ></a>{{/if}}{{if $pbAux1}}<a
                href="{{$pbAux1}}"><img {{$gifAux1}} alt="" {{$dhtml}} ></a>{{/if}}

            {{if $pbBack}}
            <!-- // make back button universal for consistence || dennis.mollel@yahoo.com @ 2010 --> 

            {{if $backToApp}}
              <a
                href="javascript:window.top.close();"><img {{$gifBack2}} alt="" {{$dhtml}} ></a>
            {{/if}}

            {{if not $backToApp}}
              <a
                href="javascript:window.history.back();"><img {{$gifBack2}} alt="" {{$dhtml}} ></a>
            {{/if}}

            {{/if}}
                {{if $pbHelp}}<a
                href="{{$pbHelp}}"><img {{$gifHilfeR}} alt="" {{$dhtml}}></a>{{/if}}{{if $breakfile}}

                {{if $closeSysAdmin}}
                <a href="javascript:top.close();"  {{$sCloseTarget}}><img {{$gifClose2}} alt="{{$LDCloseAlt}}" {{$dhtml}}></a>
                {{/if}}

                {{if not $closeSysAdmin}}
                <a href="{{$breakfile}}" {{$sCloseTarget}}><img {{$gifClose2}} alt="{{$LDCloseAlt}}" {{$dhtml}}></a>
                {{/if}}

                {{/if}}
                {{/if}}
            {{if $disableButton}}<a
                href="{{$pbHelp}}"><img {{$gifHilfeR}} alt="" {{$dhtml}}></a>{{/if}}
        </td>
    </tr>
</table>
