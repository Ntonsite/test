{{* ward_occupancy_list_row.tpl 2004-06-15 Elpidio Latorilla *}}
{{* One row for each occupant or room/bed *}}
{{* This template is used by /modules/nursing/nursing_station.php to populate the ward_occupancy_list.tpl template *}}

{{if $bToggleRowClass}}
<tr class="wardlistrow1" {{$sFlagDiag}}>
    {{else}}
<tr class="wardlistrow2" {{$sFlagDiag2}}>
    {{/if}}
    <td>{{$sMiniColorBars}} </td>
    <td>&nbsp;{{$sRoom}}</td>
    <td>&nbsp;{{$sBed}} {{$sBedIcon}}</td>
    <td>&nbsp;{{$sFamilyName}}{{$cComma}} {{$sName}}</td>
    <td>&nbsp;{{$sBirthDate}}</td>
    <td>&nbsp;{{$sPatNr}}</td>
    <td>&nbsp;{{$sAdmDate}}</td>
    <td>&nbsp;{{$sInsuranceType}}</td>
    <td>&nbsp;{{$sAllergy}}</td>
    <td>&nbsp;{{$sAdmitDataIcon}} {{$vitalSigns}} {{$sChartFolderIcon}} {{$sNotesIcon}} {{$sTransferIcon}} {{$sDischargeInfoIcon}} {{$sDischargeIcon}}{{$sTreatmentSheet}} {{$sNoDiag}} </td>
</tr>
<tr>
    <td colspan="15" class="thinrow_vspacer">{{$sOnePixel}}</td>
</tr>
