
    <div class="mt20 mb10">

        @if($proposal->progressive_billing)
            <div class="row card-body">
                <div class="col-sm-8">
                    <strong> Progressive Billing:</strong> <br/>Do you want to bill the customer after this service is
                    completed?
                </div>
                <div class="col-sm-2">
                    <div class="admin-form-item-widget">
                        YES <input type="radio" class="form-control" name="bill_after" id="bill_after1" value="1"
                                   @if($proposalDetail->bill_after) checked @endif >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="admin-form-item-widget">
                        NO <input type="radio" class="form-control" name="bill_after" id="bill_after2" value="0"
                                  @if(!$proposalDetail->bill_after) checked @endif >
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="bill_after" value="0">
        @endif

        <a id="header_calculate_combined_costing_button2" href="javascript:" class="{{ $site_button_class }} ">Save This
            Service and Stay</a>

        &nbsp;

        <a id="header_calculate_combined_costing_button3" class="{{ $site_button_class }}" href="javascript:">Save and
            Return To Proposal</a>



        <div class="row card-body">

            <table class="table table-bordered">

                @php($name = '')
                @php($cost_total = 0)

                @foreach($striping as $stripe)
                    @if($name != $stripe->name)
                        <tr>
                            <td class="info" colspan="4'"><h4>{{$stripe->name}}</h4></td>
                        </tr>
                        <tr>
                            <td class="tc">Descripton</td>
                            <td class="tc">Cost</td>
                            <td class="tc">Quantity</td>
                            <td class="tc">Total</td>
                        </tr>
                        @php($name = $stripe->name)
                    @endif

                    <tr>
                        <td class="tc">{{$stripe->description}}</td>
                        <td class="tc">{{ \App\Helpers\Currency::format($stripe->cost ?? '0.0') }}</td>
                        <td class="tc">
                            <input type="hidden" value="{{$stripe->cost}}"  name="cost_{{$stripe->id}}" />
                            <input type="text" class="form-control"
                                   id="quantity_{{$stripe->id}}"
                                   name="quantity_{{$stripe->id}}"
                                   value="{{$stripe->quantity}}"
                                   onChange="javascript:addRowTotal({{$stripe->cost}}, {{$stripe->id}})" />

                        </td>
                        <td class="tc">
                            <span class="form-control" id="total_{{$stripe->id}}">{{ \App\Helpers\Currency::format(($stripe->cost * $stripe->quantity) ?? '0.0') }}</span>
                            @php($cost_total += ($stripe->cost * $stripe->quantity))
                            <input type="hidden" name="hidden_{{$stripe->id}}" id="hidden_total_{{$stripe->id}}" value="{{$stripe->cost * $stripe->quantity}}">

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>



</div>
@push('partials-scripts')

    <script src="{{ URL::asset('/js/sweetalert2.min.js')}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/sweetalert2.min.css')}}">

    <script type="text/javascript">

        function addRowTotal(cost, service_id)
        {
            var quantity = $("#quantity_" + service_id).val();
            var total = Math.ceil(quantity * cost);
            $("#total_" + service_id).text(formatCurrency.format(total));

        }

        function total_current_selections()
        {
            var current_total= 0;

            return current_total;
        }

        var servicedesc = '{!! $service->service_template !!}';

        $(document).ready(function () {

            // when the page loads we may need to repeat some calculations to determine total costs
            // and populate other display items on the page

            var cost_total = {{$cost_total}};

            headerElCombinedCosting.text(cost_total);

            function calcme()
            {

                var profit =$("#form_header_profit").val();
                var materials = headerElCombinedCosting.val();
                var service = {{ $proposalDetail->services_id }};
                var combinedcost = 0;

                if (parseInt(profit) != profit || parseFloat(materials) != materials)  { // check these are numbers
                    showInfoAlert('You must only enter numbers for profit and additional costs.', headerAlert);

                    setTimeout(() => {
                        closeAlert(headerAlert);
                    }, 3000);

                    return;
                }


                $("input[type='hidden']").each(function() {
                    var res = $(this).attr('name').substring(0, 7);
                    if(res == "hidden_")
                    {
                        if(parseFloat(this.value) == this.value)
                        {
                            combinedcost = combinedcost + parseFloat(this.value);
                        }
                    }

                });

                $("#x_materials").val(combinedcost);

                //what was cost_total
                //what is combinedcost;
                var otcost = combinedcost;

                console.log('combined:' + combinedcost);

                var proposaltext = tinymce.activeEditor.getContent();

                if (proposaltext == '') {
                    proposaltext = servicedesc;
                }
                $("#x_proposal_text").val(proposaltext);

                overhead = Math.ceil((otcost / 0.7) - otcost);

                console.log('overhead:' +overhead);

                $("#form_header_over_head").text(formatCurrency.format(overhead));

                $("#explain").html(' 30%');

                breakeven = (parseFloat(overhead) + parseFloat(otcost));

                console.log('breakeven:' + breakeven);

                $("#form_header_break_even").text(formatCurrency.format(breakeven));

                var total_cost = (parseFloat(breakeven) + parseFloat(profit));

                $("#header_show_customer_price").text(formatCurrency.format(total_cost));

                console.log('total: ' + total_cost);

                $("#x_cost").val(total_cost);

                $("#x_material_cost").val(materials);
                $("#x_overhead").val(overhead);
                $("#x_break_even").val(breakeven);
                $("#x_profit").val(profit);


            }


            function calculate(stay) {

                calcme();
                saveit(stay);

                console.log("end striping");


            }


            function saveit(stay = false) {

                $("#stayorleave").val(stay)  // return to proposal page or service page

                $("#estimator_form").submit();

                Swal.fire({
                    title: 'Be Patient',
                    text: 'Saving your estimate.',
                    icon: 'success',
                    showConfirmButton: false,

                })
            }

            var estimatorForm = $("#estimator_form"); // form to set values for submit and save

            // when you want to calculate and save record
            $('#header_calculate_combined_costing_button2').on('click', function () {

                calculate(false);

            });

            $('#header_calculate_combined_costing_button3 ,#header_calculate_combined_costing_button4').on('click', function () {

                calculate(true);

            });

            headerElCombinedCosting.val({{$cost_total}});

            calcme();
        });
    </script>
@endpush
