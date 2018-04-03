//Wersja Oryginalna

jQuery(document).ready(function ($) {

    function c(attr) {
        console.log(attr);
    }

    function co(attr) {
        console.log(JSON.stringify(attr, null, 2));
    }

    function AjaxSelectCall(selectName) {

        var woj = $('select[name=wojewodztwa]').find(":selected");
        var powiat = $('select[name=powiaty]').find(":selected");

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'my_call',
                nonce: ajaxnonce,
                IdWoj: woj.val(),
                IdPow: powiat.val()
            },
            type: "POST",
            dataType: 'text',
            success: function (data, textStatus, XMLHttpRequest) {

                $(".loader").remove();
                $('select[name=' + selectName + ']').css("border", "1px solid green");
                $('select[name=' + selectName + ']').html(data);

//                if (data.status === 200) {
//                    c(data.content);
//                } else if (data.status === 201) {
//                    c(data.message);
//                } else {
//                    c(data);
//                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {

                c(MLHttpRequest);
                c(textStatus);
                c(errorThrown);
            },
            complete: function (data, textStatus) {

//			msg = textStatus;
//            
//			if (textStatus === 'success') {
//				//msg = data.responseJSON.found;
//			}
//            
//			console.log(data);
//			console.log(textStatus);
            }
        });
        return false;
    }

    $('select[name=wojewodztwa]').change(function () {
        //c($(this).find(":selected").val());
        //var woj = $(this).find(":selected");

        $('select[name=gminy]').html('<option value="">---</option>');
        $('select[name=powiaty]').after('<div class="loader"></div>');

        AjaxSelectCall('powiaty');
    });

    $('select[name=powiaty]').change(function () {

        //var powiat = $(this).find(":selected");

        $('select[name=gminy]').after('<div class="loader"></div>');
        $(this).css("border", "1px solid #ccc");
        AjaxSelectCall('gminy');
    });

    $('select[name=gminy]').change(function () {
        $(this).css("border", "1px solid #ccc");
    });

    function fCred() {
        $.ajax({
            url: ajaxurl,
            data: {
                action: 'my_cred_call',
                nonce: ajaxnonce
            },
            type: "POST",
            dataType: 'text',
            success: function (data, textStatus, XMLHttpRequest) {

                var cred = JSON.parse(data);
                myAuthorization(cred);


//                if (data.status === 200) {
//                    c(data.content);
//                } else if (data.status === 201) {
//                    c(data.message);
//                } else {
//                    c(data);
//                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
                c(MLHttpRequest);
                c(textStatus);
                c(errorThrown);
            },
            complete: function (data, textStatus) {

//			msg = textStatus;
//            
//			if (textStatus === 'success') {
//				//msg = data.responseJSON.found;
//			}
//            
//			console.log(data);
//			console.log(textStatus);
            }
        });

        return false;
    }

    function myAuthorization(cred) {
        $.ajax({
            /*headers: { 
             'Accept': 'application/json',
             'Content-Type': 'application/json' 
             },*/

            contentType: "application/json; charset=utf-8",
            type: "POST",
            url: 'https://doekodev.azurewebsites.net/api/v1/Account/Token',
            data: JSON.stringify({
                UserName: cred.l,
                Password: cred.p
            }),
            dataType: 'json',
            success: function (data, textStatus, XMLHttpRequest) {

                afterAuthorizationCall(data);

//                if (data.status === 200) {
//                    c(data.content);
//                } else if (data.status === 201) {
//                    c(data.message);
//                } else {
//                    c(data);
//                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {

                c(MLHttpRequest);
                c(textStatus);
                c(errorThrown);
            },
            complete: function (data, textStatus) {

//			msg = textStatus;
//			if (textStatus === 'success') {
//				msg = data.responseJSON.found;
//			}
//			console.log(data);
//			console.log(textStatus);
            }
        });

        return false;
    }

    function getOrgObj(orgType, orgName, objAddress) {

        var objType = {};

        if (orgType === 1) { //Jeżeli osoba fizyczna
            objType = {
                "FirstName": $('input[name=' + orgName + '-imie]').val(),
                "LastName": $('input[name=' + orgName + '-nazwisko]').val(),
                "PhoneNumber": $('input[name=' + orgName + '-nrtelefonu]').val(),
                "Email": $('input[name=' + orgName + '-adresemail]').val(),
                "Address": objAddress
            };
        } else { //wszystkie inne instytucje

            if (orgType === 2) { //jeżeli Firma
                objType["CompanySize"] = parseInt($('select[name=' + orgName + '-wielkosc]').val());
            }

            objType = {
                "Type": orgType,
                "Name": $('input[name=' + orgName + '-nazwa]').val(),
                "Name2": $('input[name=' + orgName + '-nazwacd]').val(),
                "TaxId": Number($('input[name=' + orgName + '-nip]').val()),
                "PhoneNumber": $('input[name=' + orgName + '-nrtelefonu]').val(),
                "Email": $('input[name=' + orgName + '-adresemail]').val(),
                "Address": objAddress
            };

        }
        return objType;
    }

    function getFormObject() {
        var objForm = {};

//          POBIERANIE ID KONTRAKTU
        var contractId = Number($('select[name=klaster]').val());
        objForm["ContractId"] = contractId;

        //var arrAddress = new Array("ulica", "nrbudynku", "nrlokalu", "kodpocztowy", "miejscowosc", "wojewodztwa", "powiaty", "gminy");
        
        var Commune = $('select[name=gminy]').val().split('');
        
//          POBIERANIE ADRESU
        var objAddress = {
            "StateId": Number($('select[name=wojewodztwa]').val()),
            "DistrictId": Number($('select[name=powiaty]').val()),
            "CommuneId": Number(Commune[0]),
            "CommuneType": Number(Commune[1]),
            "PostalCode": $('input[name=kodpocztowy]').val(),
            "City": $('input[name=miejscowosc]').val(),
            "Street": $('input[name=ulica]').val(),
            "BuildingNo": $('input[name=nrbudynku]').val(),
            "ApartmentNo": $('input[name=nrlokalu]').val()
        };

        var orgType = 0;
        var objOrgType = {};

        var orgName = ["", "osobaprywatna", "firma", "stowarzyszenie", "fundacja", "parafia", "bup"];

//          POBIERANIE TYPU ORGANIZACJI
        switch ($('select[name=rodzaj]').val()) {

            case "Osoba prywatna":
                orgType = 1;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;

            case "Firma":
                orgType = 2;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;

            case "Stowarzyszenie":
                orgType = 3;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;

            case "Fundacja":
                orgType = 4;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;

            case "Parafia":
                orgType = 5;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;

            case "Budynek Użyteczności publicznej":
                orgType = 6;
                objOrgType = getOrgObj(orgType, orgName[orgType], objAddress);
                break;
        }

        if (orgType === 1) {
            objForm["Person"] = objOrgType;
        } else {
            objForm["Organization"] = objOrgType;
        }

//          POBIERANIE TYPU INSTALACJI
        var instStatus = $('select[name=instalacja]').val();
        switch (instStatus) {

            // POSIADAM INSTALACJĘ
            case "Posiadam instalację":
                var PvPower = $('input[name=posiadaminstalacje-mocinstalacji]').val();
                PvPower = Number(PvPower.replace(",", "."));

                var PvYearlyProduction = $('input[name=posiadaminstalacje-rocznyuzysk]').val();
                PvYearlyProduction = Number(PvYearlyProduction.replace(",", "."));

                var installation = {
                    "PvPower": PvPower,
                    "PvYearlyProduction": PvYearlyProduction
                };
                objForm["ExistingInstallation"] = installation;
                break;
                //CHCĘ INSTALACJĘ
            case "Chcę instalację":
                var instType = $('select[name=chceinstalacje-rodzaj]').val();

                switch (instType) {

                    //CHCĘ INSTALACJĘ PROKONSUMENCKA
                    case "Prosumencka":

                        var PvPower = $('input[name=chceinstalacje_prokonsumencka-rocznezuzycieenergii]').val();
                        PvPower = Number(PvPower.replace(",", "."));

                        var EnYearlyConsumption = $('input[name=chceinstalacje_prokonsumencka-proponowanamoc]').val();
                        EnYearlyConsumption = Number(EnYearlyConsumption.replace(",", "."));

                        var installation = {
                            "PvPower": PvPower,
                            "EnYearlyConsumption": EnYearlyConsumption
                        };
                        objForm["NewInstallationPros"] = installation;
                        break;

                        //CHCĘ INSTALACJĘ FARMA
                    case "Farma":

                        var PvPower = $('input[name=chceinstalacje_farma-proponowanamoc]').val();
                        PvPower = Number(PvPower.replace(",", "."));

                        var installation = {
                            "PvPower": PvPower,
                            "Description": $('textarea[name=chceinstalacje_farma-stanzaawansowania]').val()
                        };
                        objForm["NewInstallationFarm"] = installation;
                        break;
                }
                break;
        }
        //c(JSON.stringify(objForm, null, 2));
        return objForm;
    }

    var ObjForm;
    document.addEventListener('wpcf7mailsent', function (e) {
        fCred();
        ObjForm = getFormObject();
    }, false);

    function afterAuthorizationCall(authorization) {
         //Wysyłanie formularza do WebApi
         
         c(ObjForm);
         co(ObjForm);
         
        $.ajax({
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'Authorization': 'bearer ' + authorization.token
            },
            
            
            /*contentType: "application/json; charset=utf-8",
            Authorization: 'bearer ' + authorization.token,*/
            
            type: "POST",
            url: 'https://doekodev.azurewebsites.net/api/v1/ClusterInvestments/Create',
            data: JSON.stringify(ObjForm),
            dataType: 'json',
            success: function (data, textStatus, XMLHttpRequest) {
                //var myObject = JSON.parse(data);

                if (data.status === 200) {
                    c(data.content);
                } else if (data.status === 201) {
                    c(data.message);
                } else {
                    c(data);
                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
                co(MLHttpRequest);
                c(textStatus);
                c(errorThrown);
            },
            complete: function (data, textStatus) {
                var msg = textStatus;
                if (textStatus === 'success') {
                    msg = data.responseJSON.found;
                }
                c(data);
                c(textStatus);
            }
        });

        return false;
    }

//    $('.btn-submit').click(function (e) {
//        fCred();
//        ObjForm = getFormObject();
//    });

});