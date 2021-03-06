{{* ward_occupancy_list.tpl  2004-05-15 Elpidio Latorilla *}}
{{* Table frame for the occupancy list *}}

<table cellspacing="0" style="width:100%;">
    <tbody>
        <tr>
            <td class="wardlisttitlerow">&nbsp;</td>
            <td class="wardlisttitlerow">{{$LDRoom}}</td>
            <td class="wardlisttitlerow">{{$LDBed}}</td>
            <td class="wardlisttitlerow">{{$LDFamilyName}}, {{$LDName}}</td>
            <td class="wardlisttitlerow">{{$LDBirthDate}}</td>
            <td class="wardlisttitlerow">{{$LDPatNr}}</td>
            <td class="wardlisttitlerow">{{$LDAdmissionDate}}</td>
            <td class="wardlisttitlerow">{{$LDInsuranceType}}</td>
            <td class="wardlisttitlerow">Allergic</td>
            <td class="wardlisttitlerow">{{$LDOptions}}</td>
        </tr>

        {{$sOccListRows}}

    </tbody>
</table>