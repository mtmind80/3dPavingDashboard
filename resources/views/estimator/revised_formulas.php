
    case 'Seal Coating':

sqft = user input
yield = user input
sealer = sqft /yield
sand = sealer * 2
additive = sealer / 50
primer = user input
fastset = user input
profit = user entered 

sandtotal = sandcost * sand
fastsettotal = fastsetcost * fastset;
primertotal=cprimercost * primer
additivetotal = additivecost * additive
sealertotal = sealercost * sealer
materials = sandtotal + fastsettotal + primertotal + additivetotal + sealertotal
    
all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.7) - combined cost);
Customer cost = Profit + combined cost + overhead



    case 'Paver Brick': //6    Paver Brick
    
sqft = user input
cost = user input
tons = user input
description = user input
material cost  = cost
profit = user entered
all sections = all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.75) - combined cost)  calculated at 25%
Customer cost = Profit + combined cost + overhead

    case 'Drainage and Catchbasins': //3    Drainage and Catchbasins


    
profit = user input
material cost  = cost
all sections = all sections = vehicles + equipment + other + subs + labor
combined cost = all sections + materials
overhead = (combined cost / 0.70) - combined cost)  calculated at 30%
Customer cost = Profit + combined cost + overhead

    case 'Sub Contractor': //10    Any Sub Contractor



    

    case 'Other': //5    Other Service

    profit = user input
    material cost  = cost
    all sections = all sections = vehicles + equipment + other + subs + labor
    combined cost = all sections + materials
    if sub has an overhead use it otherwise use standard ((combined cost + profit) / 0.7) - (combined cost + profit));
    Customer cost = Profit + combined cost + overhead

    (1000 / 0.7) - 1000 = 428.57
    
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
