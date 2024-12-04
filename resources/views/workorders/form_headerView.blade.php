<div class="mt20 mb10">
    <div class="row justify-content-lg" style="background:{{$color->color}};">
        <h2>&nbsp; {{$color->name}}</h2>
    </div>
    <div class="row mt20">
    <div class="col-sm-2">
            Service Name
        </div>
        <div class="col-sm-6">
            {{ $proposalDetail->service_name }}
        </div>
        <div class="col-sm-4">
        </div>
    </div>
    <div class="row mt20">
        <div class="col-sm-12">

            <table class="table-centered table-light full-width">
                    <tr>
                    <td class="w1-6">Customer Price:
                       $ {{$proposalDetail->cost}}
                    </td>
                    </tr>
            </table>
        </div>
    </div>
</div>

