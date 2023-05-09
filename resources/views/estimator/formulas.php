//setting local variables from form fields

//sub total
var st = $("#SubTotal").val();
if(st == '')
{
st = 0;
}
//vehicle totals
var vt = $("#POVTotal").val();
if(vt == '')
{
vt =0;
}
//equipment totals
var et = $("#POequipTotal").val();
if(et =='')
{
et=0;
}
//labortotal
var lt = $("#POlaborTotal").val();
if(lt =='')
{
lt=0;
}
othertotal
var ot = $("#POOtherTotal").val();
if(ot =='')
{
ot=0;
}
//materials total
var mt = $("#matcost").val();
if(mt =='')
{
mt=0;
}
// Set disabled display fields
$("#vcost").val(vt);
$("#ecost").val(et);
$("#mcost").val(mt);
$("#lcost").val(lt);
$("#ocost").val(ot);
$("#scost").val(st);

//Calculate cobined cost field
var combinedcost = parseFloat(vt) + parseFloat(et) + parseFloat(lt) + parseFloat(ot) + parseFloat(mt);
//update totals on form
CALCME(this.dataform,'{$details['cmpServiceCat']}','{$details.cmpServiceID}');

});



function CHECKIT(form)
{

if(!test(form)){
return;
}

var q = tinyMCE.get('jordProposalText').getContent();
tinyMCE.triggerSave();

form.submit();
}

function test(form)
{
var q = tinyMCE.get('jordProposalText').getContent();
tinyMCE.triggerSave();

combinedthis.dataform,'{$details['cmpServiceCat']}','{$details.cmpServiceID}',1);

//If your service did not meet the minimum cost for that service
if(parseFloat(form.jordCost.value) < parseFloat({$details.cmpServiceDefaultRate}))
{
alert("Your service cost is less than our minimum. ${$details.cmpServiceDefaultRate}");
return false;
}

//If you left profit blank we fill in 0
if(parseFloat(form.jordProfit.value) != form.jordProfit.value)
{
alert("You are leaving profit at 0.");
form.jordProfit.value = 0;

}

//check values
//vehicles are empty
var msg = '';
if(parseInt(form.vcost.value) == 0)
{

var msg = msg + 'You do not have any vehicles on the service.\n';
}
//equipment is empty?
if(parseInt(form.ecost.value) == 0)
{
var msg = msg + 'You do not have any equipment on the service.\n';
}

//labor is empty?
if(parseInt(form.lcost.value) == 0)
{
var msg = msg + 'You do not have any labor on the service.\n';
}


//tell the user
if(msg != '')
{
var itsok = confirm(msg);
if(!itsok)
{
return false;
}
}


showSpinner('Thank you, Please wait.');

return true;

}


function CALCME(form,stype,sid,givealert)
{

var square = 0;
var profit = $("#jordProfit").val();
if(!profit)
{
    profit = 0;
}
profit = (profit.replace(/,/g,''));
$("#jordProfit").val(profit);

switch(stype) {

    case 'Seal Coating':
    //service_category_id = 8

    if(form.jordSquareFeet.value == parseInt(form.jordSquareFeet.value) && form.jordPrimer.value == parseInt(form.jordPrimer.value) && form.jordFastSet.value == parseInt(form.jordFastSet.value))
    {


        var multiplyer = Math.ceil(form.jordSquareFeet.value / form.jordYield[form.jordYield.selectedIndex].value);
        //calculate amounts

sqft = user input
yield = user input
sealer = sqft /yield
sand = sealer * 2
additive = sealer / 50
primer = user input
fastset = user input

sandtotal = sandcost * sand
fastsettotal = fastsetcost * fastset;
primertotal=cprimercost * primer
additivetotal = additivecost * additive
sealertotal = sealercost * sealer
material cost  total all above
all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.7) - combined cost);
Customer cost = Profit + combined cost + overhead

square = form.jordSquareFeet.value;
        /*
        SEALER  = Size/Yield  = GAL SEALER
        AND SAND = GAL SEALER * 2
        ADDITIVE = AND GAL SEALER / 50
        */
        var primer = form.jordPrimer.value;
        var fastset = form.jordFastSet.value;

        var sealer = multiplyer;
        var sand = Math.ceil(multiplyer * 2);
        var additive = Math.ceil(multiplyer / 50);
        form.jordSand.value = sand;
        form.jordSealer.value = sealer;
        form.jordAdditive.value = additive;
        var sealercost = form.sealer.value;
        var sandcost = form.sand.value;
        var additivecost = form.additive.value;
        var primercost = form.primer.value;
        var fastsetcost = form.fastset.value;


        var sandtotal = Math.ceil(parseFloat(sandcost) * parseFloat(sand));
        var fastsettotal = Math.ceil(parseFloat(fastsetcost) * parseFloat(fastset));
        var primertotal = Math.ceil(parseFloat(primercost) * parseFloat(primer));
        var additivetotal = Math.ceil(parseFloat(additivecost) * parseFloat(additive));
        var sealertotal = Math.ceil(parseFloat(sealercost) * parseFloat(sealer));

        form.SandTotal.value = '$' + sandtotal.toFixed(2);
        form.FastSetTotal.value = '$' + fastsettotal.toFixed(2);
        form.SealerTotal.value = '$' + sealertotal.toFixed(2);
        form.PrimerTotal.value = '$' + primertotal.toFixed(2);
        form.AdditiveTotal.value = '$' + additivetotal.toFixed(2);

        var subtotal = Math.ceil(
            parseFloat(sandtotal)  +
            parseFloat(fastsettotal) +
            parseFloat(primertotal) +
            parseFloat(additivetotal) +
            parseFloat(sealertotal)
        );

        //Materials cost
        form.mcost.value = subtotal;

        //total up
        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);

        var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
        var overhead = Math.ceil((otcost / 0.7) - otcost);
        $("#explain").html('calculated at 30%');
        //var overhead = Math.ceil((parseFloat(combinedcost) +  parseFloat(profit)) * 30)/100;


        var str = form.jordProposalText.value;
        var newstr = str.replace('@@SQFT@@', form.jordSquareFeet.value);
        var newstr = newstr.replace('@@PHASES@@', form.jordPhases.value);
        form.jordProposalText.value = newstr;


    } else {
        if(givealert == 1)
        {
            alert("You must select a yield, enter square feet, and gallons of primer and fastset.");
        }
    }


    break;

    case 'Paver Brick': //6    Paver Brick

    if(form.jordSquareFeet.value == parseInt(form.jordSquareFeet.value) && form.jordCostPerDay.value == parseInt(form.jordCostPerDay.value) && form.jordVendorServiceDescription.value != '')
    {
        form.mcost.value = form.jordCostPerDay.value;
        var profit = form.jordProfit.value;
        if (profit =='')
        {
            profit = 0;
        }
        square = form.jordSquareFeet.value;
        //total up
        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);

        var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
        var overhead = Math.ceil((otcost / 0.75) - otcost);
        $("#explain").html('calculated at 25%');
        // set cost

sqft = user input
cost = user input
tons = user input
description = user input
material cost  = cost
combined cost = all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.75) - combined cost)  calculated at 25%
Customer cost = Profit + combined cost + overhead

        var str = form.jordProposalText.value;
        var newstr = str.replace('@@SQFT@@', form.jordSquareFeet.value);
        newstr = newstr.replace('@@TONS@@', form.jordTons.value);
        form.jordProposalText.value = newstr;


    } else {
        if(givealert == 1)
        {
            alert("You must fill in a cost, sq. ft. and description.");
        }
    }

    break;


    case 'Drainage and Catchbasins': //3    Drainage and Catchbasins

    if(form.jordAdditive.value == parseInt(form.jordAdditive.value) && form.jordCostPerDay.value == parseInt(form.jordCostPerDay.value) && form.jordVendorServiceDescription.value != '')
    {
        form.mcost.value = form.jordCostPerDay.value;
        var profit = form.jordProfit.value;
        if (profit =='')
        {
            profit = 0;
        }

        //total up
        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);

profit = user input
material cost  = cost
combined cost = all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.70) - combined cost)  calculated at 30%
Customer cost = Profit + combined cost + overhead

        var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
        var overhead = Math.ceil((otcost / 0.7) - otcost);
        $("#explain").html('calculated at 30%');


        var str = form.jordProposalText.value;
        var newstr = str.replace('@@BASINS@@', form.jordAdditive.value);
        form.jordProposalText.value = newstr;


    } else {
        if(givealert == 1)
        {
            alert("You must fill in a cost, number of drains and description.");
        }
    }


    break;

    case 'Sub Contractor': //10    Any Sub Contractor


        if(form.jordAdditive.value == 0 && form.boh.value == parseInt(form.boh.value))
        {
            form.jordAdditive.value = form.boh.value;
        }

        if(form.jordAdditive.value == parseInt(form.jordAdditive.value) && form.jordCostPerDay.value == parseInt(form.jordCostPerDay.value) && form.jordVendorServiceDescription.value != '')
        {

        form.mcost.value = form.jordCostPerDay.value;
        var profit = form.jordProfit.value;
        if (profit =='')
        {
            profit = 0;
        }

        //total up
        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);

        var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
        if(form.jordAdditive.value = parseInt(form.jordAdditive.value))
        {
            var soh = parseFloat(1-(form.jordAdditive.value / 100));
            // alert(soh + '-' + otcost);
            overhead = Math.ceil((otcost/soh) - otcost);

            $("#explain").html('calculated at ' + form.jordAdditive.value + '%');
        } else // sub has no overhead value use standard
        {
            var overhead = Math.ceil((otcost / 0.7) - otcost);
            $("#explain").html('calculated at 30%');
        }


    } else {
        if(givealert == 1)
        {
            alert("You must fill in a cost and description and over head rate.");
        }
        //form.jordAdditive.value = 0;

    }


    break;


    case 'Other': //5    Other Service


        if(form.jordCostPerDay.value == parseInt(form.jordCostPerDay.value) && form.jordVendorServiceDescription.value != '')
        {
            form.mcost.value = form.jordCostPerDay.value;
            var profit = form.jordProfit.value;
            if (profit =='')
            {
                profit = 0;
            }

        //total up
        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
        //set over head to zero for other
        var overhead = 0;//Math.ceil((parseFloat(combinedcost) +  parseFloat(profit)) * 0.3);


    } else {
        if(givealert == 1)
        {
            alert("You must fill in a cost and description.");
        }
    }


    break;


    case 'Rock': //7    Rock Services
        /*
        2 -Rock
        Cost_calculation Size x (7/1080) x Depth  = TONS
        Tons/18 = LOADS (Rounded UP to nearest whole number)
        
        */
        //  fill in tons and loads
        if(form.jordSquareFeet.value == parseInt(form.jordSquareFeet.value) && form.jordDepthInInches.value == parseInt(form.jordDepthInInches.value))
        {

            varnish = (7/1080);
            tons = sqft * varnish  * depth
            loads = tons/18
            rockcost = rockcost.value;
            materials = (tons * rockcost);
            profit = user entered
            combined cost = all sections = vehicles + equipment + other + subs + labor
            combined cost = all sections + materials
            overhead = (combined cost / 0.70) - combined cost)  calculated at 30%
            Customer cost = Profit + combined cost + overhead
            
            var str = form.jordProposalText.value;
            var newstr = str.replace('@@INCHES@@', form.jordDepthInInches.value);
            form.jordProposalText.value = newstr;
            
        }
        else
        {
            if(givealert == 1)
            {
                alert('You must fill in square feet and depth in inches.');
            }
        }


    break;

case 'Excavation':  //4    All Excavation
        //  fill in tons and loads
        if(form.jordSquareFeet.value == parseInt(form.jordSquareFeet.value) && form.jordDepthInInches.value == parseInt(form.jordDepthInInches.value)  && form.jordCostPerDay.value == parseInt(form.jordCostPerDay.value))
        {

sqft = user entered
depth = user entered
materials = our cost = user entered
tontimes = (7/1080)
tons = sqft * tontimes * depth
loads = (tons/18)
profit = user entered
combined cost = all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.70) - combined cost)  calculated at 30%
Customer cost = Profit + combined cost + overhead

$("#jordTons").val(tons);
            $("#jordLoads").val(loads);
            var ourcost = form.jordCostPerDay.value;
            form.mcost.value = ourcost;
            var profit = form.jordProfit.value;
            if (profit =='')
            {
            profit = 0;
            }
            //total up
            var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
            //set over head
            var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
            var overhead = Math.ceil((otcost / 0.7) - otcost);
            $("#explain").html('calculated at 30%');
            
            
            var str = form.jordProposalText.value;
            var newstr = str.replace('@@TONS@@', tons);
            form.jordProposalText.value = newstr;
            
        } else {
            if(givealert == 1)
            {
                alert('You must enter our cost, based on tons and loads, sq. ft. and depth in inches.');
            }
            
        }
        
        
    break;

case 'Concrete':  //2 concrete service _id 6 -14

        var curbcost = form.curbmix.value;
        var drumcost = form.drummix.value;
        
        if (service_id < 12)
        {
        
                //linear feet
                if(form.jordLinearFeet.value == parseInt(form.jordLinearFeet.value))
                {

linear feet = user enered
locations = user entered
cubic yards = lf / 60
materials = cy * curbcost
if(service_id ==6)
{
cy = Math.ceil(lf/60);
}

if(service_id ==7)
{
cy = Math.ceil(lf/21);
}
if(service_id ==8)
{
cy = Math.ceil(lf/30);
}
if(service_id ==9)
{
cy = Math.ceil(lf/15);
}
if(service_id ==10)
{
cy = Math.ceil(lf/22);
}

if(service_id ==11)
{
cy = Math.ceil(lf/25);
}

                    square =form.jordLinearFeet.value;
                    var lf = form.jordLinearFeet.value;
                    var cy = Math.ceil(lf/60);
                    /*
                    6    Concrete    Curb (Extruded) [New or Repairs]  (lf/60)
                    7    Concrete    Curb (Type D) [New or Repairs]    (lf/21)
                    8    Concrete    Curb (Type Mod D) [New or Repairs] (lf/30)
                    9    Concrete    Curb (Type F) [New or Repairs]    (lf/24)
                    10    Concrete    Curb (Valley Gutter) [New or Repairs] (lf/15)
                    11    Concrete    Curb (Header) [New or Repairs]    (lf/25)
                    */
                    
                    
                    if(service_id ==6)
                    {
                    cy = Math.ceil(lf/60);
                    }
                    
                    if(service_id ==7)
                    {
                    cy = Math.ceil(lf/21);
                    }
                    if(service_id ==8)
                    {
                    cy = Math.ceil(lf/30);
                    }
                    if(service_id ==9)
                    {
                    cy = Math.ceil(lf/15);
                    }
                    if(service_id ==10)
                    {
                    cy = Math.ceil(lf/22);
                    }
                    
                    if(service_id ==11)
                    {
                    cy = Math.ceil(lf/25);
                    }
                    
                    
                    $("#jordCubicYards").val(cy);
                    //materials cost
                    form.mcost.value = Math.ceil(cy * curbcost);
                    
                    
                    var profit = form.jordProfit.value;
                    if (profit =='')
                    {
                        profit = 0;
                    }
                    //total up
                    var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
                    //set over head
                    var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
                    var overhead = Math.ceil((otcost / 0.7) - otcost);
                    $("#explain").html('calculated at 30%');
                    
                    
                    var str = form.jordProposalText.value;
                    var newstr = str.replace('@@TONS@@', cy);
                    form.jordProposalText.value = newstr;
                    
                } else {
                    if(givealert == 1)
                    {
                        alert('You must fill in linear feet.');
                    }
                }
                
            }
            else
            {
                /*depth and sqq ft sid 12, 13 14
                12    Concrete    Slab [New or Repairs]    (SF * Depth)/300
                13    Concrete    Ramp [New or Repairs]    (SF * Depth)/300
                14    Concrete    Sidewalks [New or Repairs]    (SF * Depth)/300
                */
                
                if(form.jordSquareFeet.value == parseInt(form.jordSquareFeet.value) && form.jordDepthInInches.value == parseInt(form.jordDepthInInches.value))
                {
                    square =form.jordSquareFeet.value;
                    var cy = Math.ceil((form.jordSquareFeet.value * form.jordDepthInInches.value)/300);
                    
                    $("#jordCubicYards").val(cy);
                    
                    form.mcost.value = Math.ceil(cy * drumcost);
                    
                    var profit = form.jordProfit.value;
                    if (profit =='')
                    {
                    profit = 0;
                    }
                    //total up
                    var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
                    //set over head
                    var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
                    var overhead = Math.ceil((otcost / 0.7) - otcost);
                    $("#explain").html('calculated at 30%');
                    
                    var str = form.jordProposalText.value;
                    var newstr = str.replace('@@INCHES@@', form.jordDepthInInches.value);
                    var newstr = newstr.replace('@@TONS@@', tons);
                    form.jordProposalText.value = newstr;
                    
            } else {
                if(givealert == 1)
                {
                    alert('You must fill in square feet and depth in inches.');
                }
            }
        
        
        }
        
        
        break;
    case 'Asphalt':


        if (sid == 19) {
        
            /*
            
            jordSquareFeet
            jordDays
            jordCostPerDay
            jordLocations
            jordDepthInInches
            jordSQYards
            jordLoads
            fill out sq yrds , depth in inches days and cost per day
            set sqyrds
            loads
            cost
            calc loads and
            total cost by cost per day * days
            
            */
            
            if (parseFloat(form.jordCostPerDay.value) == form.jordCostPerDay.value && parseFloat(form.jordDays.value) == form.jordDays.value && parseFloat(form.jordSquareFeet.value) == form.jordSquareFeet.value &&  parseFloat(form.jordDepthInInches.value) == form.jordDepthInInches.value)
            {
            square =form.jordSquareFeet.value;
            var sqyrd = Math.ceil(form.jordSquareFeet.value/9);
            form.jordSQYards.value = sqyrd;
            //var loads = Math.ceil((form.jordSquareFeet.value * form.jordDepthInInches.value) * (7/2160));
            var loads = Math.ceil(((form.jordSquareFeet.value * form.jordDepthInInches.value) /180)/20);
            $("#jordLoads").val(loads);
            form.mcost.value = parseFloat(form.jordCostPerDay.value) * parseInt(form.jordDays.value);
            var profit = form.jordProfit.value;
            if (profit =='')
            {
            profit = 0;
            }
            
            //total up
            var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
            //set over head
            var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
            var overhead = Math.ceil((otcost / 0.88) - otcost);
            $("#explain").html('calculated at 12%');
            
            
            var str = form.jordProposalText.value;
            var newstr = str.replace('@@SQFT@@', form.jordSquareFeet.value);
            form.jordProposalText.value = newstr;
        
        } else {
            if(givealert == 1)
            {
                alert("You must fill in default values, square feet, depth in inches, days, and cost per day");
            }
            
        }
        
        
        } else if(sid == 4 || sid == 5 || sid == 22 || sid == 3) {
        
        /*
        toncost
        tackcost
        
        jordSquareFeet
        
        jordDepthInInches
        set to tons  x toncost
        
        set to tack *jordTackCost
        tons = (Size x Depth)/162
        Tack gallons = sq yards/108
        jordSQYards
        
        */
        
        if (parseFloat(form.jordSquareFeet.value) == form.jordSquareFeet.value &&  parseFloat(form.jordDepthInInches.value) == form.jordDepthInInches.value)
        {

            square = form.jordSquareFeet.value;
            var toncost = form.toncost.value;
            var tackcost = form.tackcost.value;
            var sqyrd = Math.ceil(form.jordSquareFeet.value/9);
            form.jordSQYards.value = sqyrd;
            var tonamount = Math.ceil((form.jordSquareFeet.value * form.jordDepthInInches.value)/162);
            form.jordTons.value = tonamount;
            if(sid == 4 || sid == 22)
            {
                var tackamount = Math.ceil(form.jordSquareFeet.value/324);

            } else {
                var tackamount = Math.ceil(form.jordSquareFeet.value/108);
        
            }
            var totaltack = tackcost * tackamount;
            var totaltons = toncost * tonamount;
            
            form.jordTackCost.value = totaltack;
            form.jordTonCost.value = totaltons;
            //cost of goods
            form.mcost.value = parseFloat(totaltack + totaltons);
            
            
            var profit = form.jordProfit.value;
            if (profit =='')
            {
            form.jordProfit.value = 0;
            profit = 0;
            }
            
            //total up
            var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(form.mcost.value);
            //set over head
            var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
            if(sid == 4 || sid == 22) {
                var overhead = Math.ceil((otcost / 0.8) - otcost);
                $("#explain").html('calculated at 20%');
            } else {
                var overhead = Math.ceil((otcost / 0.7) - otcost);
                $("#explain").html('calculated at 30%');
            
            }
            
            
            var str = form.jordProposalText.value;
            var find = '@@INCHES@@';
            var re = new RegExp(find, 'g');
            var newstr = str.replace(re, form.jordDepthInInches.value);
            
            if(sid == 5)
            {
                var find = '@@TONS@@';
                var re = new RegExp(find, 'g');
                newstr = newstr.replace(re, tonamount);
            
            }
                form.jordProposalText.value = newstr;
            
        } else {
            if(givealert == 1)
            {
            alert("You must fill in default values, square feet, depth in inches");
            }
            
        }
        
    
    } else {
    
    }


    break;

default:

}

    var ttlcost = Math.ceil( parseFloat(combinedcost) +  parseFloat(profit) +  parseFloat(overhead) + parseFloat(form.scost.value));
    // now add subs
    var zcost = Math.ceil( parseFloat(combinedcost) +  parseFloat(profit) +  parseFloat(overhead));
    if (stype != 'Sub Contractor')
    {
        var zcost = ttlcost;
    }
    if(square > 0)
    {
        zcost = (zcost/square);
        form.costper.value = '$' + zcost.toFixed(2);
    
    
    if(stype == 'Seal Coating')
    {
        form.costper.value = '$' + zcost.toFixed(4);
    }
    }
    else
    {
        form.costper.value = 'NA';
    }
    form.jordBreakeven.value = parseFloat(overhead) + parseFloat(combinedcost);
    form.jordOverhead.value = overhead;
    form.combinedcost.value =  combinedcost;
    form.jordCost.value = ttlcost;
    form.jordCostD.value = '$' + ttlcost.toFixed(2);
    
    //alert if not minimum
    if (parseFloat(ttlcost) < parseFloat({$details.cmpServiceDefaultRate}))
    {
        alert('The total of this proposal ($' + ttlcost.toFixed(2) + ') does not meet our minimum cost ($'+ {$details.cmpServiceDefaultRate|money_format:2} + ').');
    }
}
