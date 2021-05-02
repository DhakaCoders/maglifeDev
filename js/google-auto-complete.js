var dev_mode = app_config.dev_mode;

function stateFilter(candidateStates, states) {
    var state_short_name = '';
    $.each(states, function(key, value) {
        for(var ii = 0; ii < candidateStates.length; ii++){
            var p = new RegExp("\\b^" + candidateStates[ii] + "\\b");
            if (p.test(value['name'])) {
                state_short_name = key;
                break;
            }
            
            var p1 = new RegExp("\\b" + value['name'] + "\\b");
            if (p1.test(candidateStates[ii])) {
                state_short_name = key;
                break;
            }
        }
    });
    if(state_short_name !== ''){
        return state_short_name;
    }
    $.each(states, function(key, value) {
        for(var ii = 0; ii < candidateStates.length; ii++){
            var candidateState = candidateStates[ii].replace(/^county\s+/i, '');
            var p = new RegExp("\\b" + candidateState + "\\b");
            if (p.test(value['name'])) {
                state_short_name = key;
                break;
            }            
        }

    });
    
    return state_short_name;
}

function checkMasking(prefix) {
    if (typeof validator_data != 'undefined' && validator_data.hasOwnProperty('enable_ca_statecode_validation') ) {
        var c_code = $("[name="+prefix+"Country]").val();
        var c_code_mask = validator_data[c_code.toLowerCase()+'_state_code_mask'];
        if ( c_code_mask) {
            $('input[name='+prefix+'Zip]').mask(validator_data[c_code.toLowerCase()+'_state_code_mask']);
        }else{
            $('input[name='+prefix+'Zip]').unmask();
        }
    }
}

$(function() {
    if (typeof event_type === 'undefined' || event_type == '') {
        event_type = 'blur';
    }
    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    if (typeof autopopulate_by != 'undefined' && autopopulate_by == 'address') {
        var addressSelect = $('input[name$=Address1]');
    } else {
        var addressSelect = $('input[name=zip], input[name$=Zip]');
    }
    console.log(addressSelect);
    var prevVal;
    addressSelect.on(event_type, function() {
        var components, city, state, country, availableCountry, postal_code;
        var availableStates, candidateStates = [];
        var selector = $(this);
        delay(function() {
            if (selector.val().length < 4 && event_type == 'keyup') {
                return;
            }
            if (typeof prevVal !== 'undefined' && selector.val() === prevVal) {
                return;
            }
            prevVal = selector.val();
            var address = '';
            var isTrigger = true;
            var prefix = '';
            if (selector.attr('name').match(/Zip$/)) {
                prefix = selector.attr('name').replace(/Zip$/, '');
            } else if (selector.attr('name').match(/Address1$/)) {
                prefix = selector.attr('name').replace(/Address1$/, '');
            }
            var selectedState = "";
            var isSingleCountry = false;
            
            /**
             * 
             * Single county detection while uncommon country is used
             */
            var country = $("[name$=Country]").val();
            var countryOption = $("[name$=Country] option").length;
            if(countryOption < 3 || country != '')
            {
                isSingleCountry = true;
            }   
            
            if(country == '')
            {
                country = $("[name=shippingCountry]").val();
            }
            
            if (typeof google != 'undefined') {
                var geocoder = new google.maps.Geocoder();
                var componentRestrictions = {};
                var byPassCountries = ['US'];
                if(autopopulate_by === 'zip' && !disable_component_restriction){
                    componentRestrictions['postalCode'] = selector.val();
                    if(isSingleCountry && typeof(country) != 'undefined' && byPassCountries.indexOf(country) == -1)
                    {
                        componentRestrictions['country'] = country;
                    }
                }
                console.log(componentRestrictions);
                geocoder.geocode({
                    'address': selector.val(),
                    componentRestrictions: componentRestrictions
                }, function(results, status) {
                    if (dev_mode == "Y") {
                        console.log(results, status);
                    }
                    if (status !== 'OK' || results.length === 0) {
                        return;
                    }
                    if (!results[0].hasOwnProperty('address_components')) {
                        return;
                    }
                    components = results[0].address_components;
                    var citySelected = false;
                    var stateSelected = false;
                    for (var i in components) {
                        if (
                            components[i].types.indexOf('locality') !== -1 || 
                            components[i].types.indexOf('sublocality') !== -1 ||
                            components[i].types.indexOf('neighborhood') !== -1
                        ) {
                            city = components[i].long_name;
                            citySelected = true;
                        } else if (components[i].types.indexOf('postal_town') !== -1 && !citySelected) {
                            city = components[i].long_name;
                            citySelected = true;
                        } else if (
                                components[i].types.indexOf('administrative_area_level_2') !== -1 &&
                                components[i].types.indexOf('political') !== -1 && !citySelected
                            ) {
                            city = components[i].long_name;
                            citySelected = true;
                        } else if (
                                components[i].types.indexOf('administrative_area_level_1') !== -1 && !citySelected
                            ) {
                            city = components[i].long_name;
                        }
                        
                        if (
                            components[i].types.indexOf('administrative_area_level_1') !== -1
                        ) {
                            candidateStates.push(components[i].long_name);
                            stateSelected = true;
                        }
                        if (components[i].types.indexOf('administrative_area_level_2') !== -1 && !stateSelected) {
                            candidateStates.push(components[i].long_name);
                            stateSelected = true;
                        }
                        if (
                            components[i].types.indexOf('political') !== -1 &&
                            components[i].types.indexOf('country') === -1 && 
                            !stateSelected
                        ) {
                            candidateStates.push(components[i].long_name);
                            stateSelected = true;
                        }
                        if (components[i].types.indexOf('postal_code') !== -1) {
                            postal_code = components[i].long_name;
                        }
                        if (components[i].types.indexOf('country') !== -1) {
                            country = components[i].short_name;
                        }
                    }
                    availableCountry = (app_config.allowed_country_codes.indexOf(country) > -1);
                    if (availableCountry == false) {
                        return;
                    }
                    availableStates = app_config.countries[country]['states'];
                    selectedState = stateFilter(candidateStates, availableStates);
                    if (dev_mode == "Y") {
                        console.log(city + ',' + selectedState + ',' + country);
                    }
                    if (typeof autopopulate_by != 'undefined' && autopopulate_by == 'address') {
                        $("[name=" + prefix + "Zip]").val(postal_code);
                    }
                    $("[name=" + prefix + "City]").val(city);
                    $("[name=" + prefix + "City]").trigger('blur');
                    $("[name$=Country]").val(country).change();
                    if (isTrigger) {
                        var timmer = setTimeout(function() {
                            if ($("[name=state] option, [name$=State] option").length) {
                                if (selectedState !== '') {
                                    $("[name=state], [name=" + prefix + "State]").val(selectedState);
                                    $("[name=state], [name=" + prefix + "State]").trigger('blur');
                                }
                                else{
                                    selectedState = stateFilter([city], availableStates);
                                    $("[name=state], [name=" + prefix + "State]").val(selectedState);
                                    $("[name=state], [name=" + prefix + "State]").trigger('blur');
                                }
                            } else if ($("[name=state] [type=text], [name$=State] [type=text]").length) {
                                if (selectedState !== '') {
                                    $("[name=state], [name=" + prefix + "State]").val(selectedState);
                                    $("[name=state], [name=" + prefix + "State]").trigger('blur');
                                }
                                else{
                                    selectedState = stateFilter([city], availableStates);
                                    $("[name=state], [name=" + prefix + "State]").val(selectedState);
                                    $("[name=state], [name=" + prefix + "State]").trigger('blur');
                                }
                            }
                        }, 500);
                        checkMasking(prefix);
                    }
                });
            }
        }, 1000);
    });
});