<div class="header">
    <table width="100%">
        <tr>
            <td width="50%" class="no-border">
                <img src="src/image/logo/cies.png" width="40px" />
            </td>
            <td width="50%" class="no-border right">
                @if(!empty($info))
                    {!! $info !!}
                @else
                    <div  style="padding-top: 10px">{!! \App\Http\Helpers\Util::getInspireCIES(true) !!}</div>
                @endif
            </td>
        </tr>
    </table>
    <hr />
</div>