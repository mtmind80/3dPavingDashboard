<div class="mt20 mb10">
    <form action="#" id="cost_formula_form" class="custom-validation admin-form">


        <div class="row card-body">

            <table class="table table-bordered">

                @php($name = '')

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
                            <input type="text" class="form-control"
                                              
                                              id="quantity_{{$stripe->id}}" 
                                              name="quantity_{{$stripe->id}}"
                                              value="{{$stripe->quantity}}"
                                              onChange="javascript:addTotal({{$stripe->cost}}, {{$stripe->id}})" />
                        </td>
                        <td class="tc">
                            <input type="text"  class="form-control"
                                            id="total_{{$stripe->id}}"
                                            name="total_{{$stripe->id}}"
                                            value="{{ \App\Helpers\Currency::format(($stripe->cost * $stripe->quantity) ?? '0.0') }}" /> 
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>


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

    </form>

</div>
@push('partials-scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            
            function addTotal(cost, service_id)
            {
                alert("yes" + cost);
                var quantity = $("#quantity_" . service_id).val();
                var total = Math.ceil(quantity * cost);
                $("#total_" . service_id).val(total);

            }
            // when the page loads we may need to repeat some calculations to determine total costs
            // and populate other display items on the page


            function calculate(cost_form, estimatorForm, services_id, proposal_detail_id, proposal_id, serviceCategoryId, dosave) {

                var servicedesc = '{!! $service->service_template !!}';
                var profit = $("#form_header_profit").val();
                var breakeven = '{{$proposalDetail->break_even}}';
                var overhead = '{{$proposalDetail->overhead}}';
                var materials = 0;
                var proposaltext = tinymce.activeEditor.getContent();
                var service = {{ $proposalDetail->services_id }};


                if (parseInt(profit) != profit) { // check these are numbers
                    showInfoAlert('You must only enter numbers for profit.', headerAlert);

                    setTimeout(() => {
                        closeAlert(headerAlert);
                    }, 3000);

                    return;
                }


                console.log("end striping");

                //set these fields for all services
                var bill_after = $('input[name="bill_after"]:checked').val();
                $("#x_bill_after").val(bill_after);

                var servicename = $("#service_name").val();
                $("#x_service_name").val(servicename)

                var proposaltext = tinymce.activeEditor.getContent();
                $("#x_proposal_text").val(proposaltext);

                $("#x_overhead").val(overhead);
                $("#x_break_even").val(breakeven);
                $("#x_profit").val(profit);


                //then save it
                if (dosave == 1) {
                    saveit(false);
                }
                if (dosave == 2) {
                    saveit(true);
                }

            }


            function saveit($leave = false) {

                if ($leave) {
                    $("#stayorleave").val("true")  // return to proposal page or service page

                }

                $("#estimator_form").submit();

            }

            var cost_form = $("#cost_formula_form");  // values to determine cost
            var estimatorForm = $("#estimator_form"); // form to set values for submit and save

            // when you want to calculate and save record 
            $('#header_calculate_combined_costing_button2').on('click', function () {

                calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, 1);

            });

            $('#header_calculate_combined_costing_button3 ,#header_calculate_combined_costing_button4').on('click', function () {

                calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, 2);

            });
            
        });
    </script>
@endpush
