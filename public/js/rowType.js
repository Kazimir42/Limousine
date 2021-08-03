function displayStockForm(){
    let theForm = document.getElementById("rowForm");
    theForm.style.display = "inherit";
    document.getElementById("label-toSearch").innerText = "Stock to search"

    clearForm();

    let inputSearch = document.getElementById("container-search");
    let inputResult = document.getElementById("container-result");
    inputResult.style.display = "inherit";
    inputSearch.style.display = "inherit";

}
function displayEtfForm(){
    let theForm = document.getElementById("rowForm");
    theForm.style.display = "inherit";
    document.getElementById("label-toSearch").innerText = "ETF to search"

    clearForm();

    let inputSearch = document.getElementById("container-search");
    let inputResult = document.getElementById("container-result");
    inputResult.style.display = "inherit";
    inputSearch.style.display = "inherit";
}

function displayCryptoForm(){
    let theForm = document.getElementById("rowForm");
    theForm.style.display = "inherit";
    document.getElementById("label-toSearch").innerText = "Crypto to search"

    clearForm();

    let inputSearch = document.getElementById("container-search");
    let inputResult = document.getElementById("container-result");
    inputResult.style.display = "inherit";
    inputSearch.style.display = "inherit";
}

function displayBasicForm(){
    let theForm = document.getElementById("rowForm");
    theForm.style.display = "inherit";

    clearForm();

    let inputSearch = document.getElementById("container-search");
    let inputResult = document.getElementById("container-result");
    inputResult.style.display = "none";
    inputSearch.style.display = "none";
}







function clearForm(){
    let inputNumber = document.getElementById("row_number");
    let inputValue = document.getElementById("row_value");
    let inputTotalValue = document.getElementById("row_totalValue");
    let inputDevise = document.getElementById("row_devise");
    let symboleInput = document.getElementById("row_symbol");
    let inputName = document.getElementById("row_name");
    let inputSearch = document.getElementById("row_search");
    let inputResult = document.getElementById("row_result");


    //CLEAR ALL INPUT
    inputNumber.value = "";
    inputValue.value = "";
    inputTotalValue.value = "";
    symboleInput.value = "";
    inputDevise.value = "";
    inputName.value = "";
    inputSearch.value = "";
    inputResult.value = "";


    inputName.setAttribute("disabled", "disabled");
    inputResult.setAttribute("disabled", "disabled");
    inputNumber.setAttribute("disabled", "disabled");
    inputValue.setAttribute("readonly", "readonly");
    inputTotalValue.setAttribute("readonly", "readonly");
    inputDevise.setAttribute("disabled", "disabled");

}





function hydrateType(theInput){
    document.getElementById("row_type").value = theInput.innerText;
    $theType = document.getElementById("row_type").value;

    getTheNiceTypeOfValue($theType);
}

function getTheNiceTypeOfValue($theType){
    let inputSearch = document.getElementById("row_search");


    switch ($theType){
        case "STOCK":
            inputSearch.setAttribute("onchange", "getStocks()");
            break
        case "ETF":
            inputSearch.setAttribute("onchange", "getETFs()");
            break
        case "CRYPTO":
            inputSearch.setAttribute("onchange", "getCryptos()");
            break
        case "OTHER":
            stopDisableInput();
            break
    }
}


function stopDisableInput(){
    let inputNumber = document.getElementById("row_number");
    let inputValue = document.getElementById("row_value");
    let inputTotalValue = document.getElementById("row_totalValue");
    let inputDevise = document.getElementById("row_devise");
    let inputName = document.getElementById("row_name");

    inputNumber.removeAttribute("disabled");
    inputValue.removeAttribute("readonly");
    //inputTotalValue.removeAttribute("readonly");
    inputDevise.removeAttribute("disabled");
    inputName.removeAttribute("disabled");
    inputDevise.value = "USD";

    let containerCurrency = document.getElementById("container-input-devise");
    containerCurrency.innerHTML = '<select id="row_devise" name="row[devise]" class="form-select"><option value="USD">USD</option><option value="EUR">EUR</option></select>'

}





/**
 *
 * @description: functions for stocks
 *
**/

function getStocks(){
    let formTypeValue = document.getElementById("row_search").value;

    //icone de cahrgement
    let inputResult = document.getElementById("row_result");
    inputResult.innerHTML = '<option value="NULL">Loading...</option>';
    let req = new XMLHttpRequest();
    req.open("GET", "/limousine/public/call/api/stock/search?symbol=" + formTypeValue, true);
    req.onload = getResponseStocks;
    req.send();
}


function getResponseStocks(){

    let data = JSON.parse(this.responseText);
    let datas = data["bestMatches"];

    let arrayStocksInfo = [];

    for (const key in datas) {
        let array = [];

        array.push(datas[key]['1. symbol'])
        array.push(datas[key]['2. name'])
        array.push(datas[key]['4. region'])
        array.push(datas[key]['8. currency'])

        arrayStocksInfo.push(array);

    }
    updateBaseInput(arrayStocksInfo);
}


/**
 *
 * @description: functions for ETFs
 *
 **/


function getETFs(){
    let formTypeValue = document.getElementById("row_search").value;

    //icone de cahrgement
    let inputResult = document.getElementById("row_result");
    inputResult.innerHTML = '<option value="NULL">Loading...</option>';

    let req = new XMLHttpRequest();
    req.open("GET", "/limousine/public/call/api/etf/search?symbol=" + formTypeValue, true);
    req.onload = getResponsETFs;
    req.send();
}

function getResponsETFs(){
    let data = JSON.parse(this.responseText);
    let datas = data["bestMatches"];

    let arrayETFsInfo = [];

    for (const key in datas) {
        let array = [];

        array.push(datas[key]['1. symbol'])
        array.push(datas[key]['2. name'])
        array.push(datas[key]['4. region'])
        array.push(datas[key]['8. currency'])

        arrayETFsInfo.push(array);

    }
    updateBaseInput(arrayETFsInfo);
}



/**
 *
 * @description: functions for crypto
 *
 **/

function getCryptos(){
    let formTypeValue = document.getElementById("row_search").value;

    //icone de cahrgement
    let inputResult = document.getElementById("row_result");
    inputResult.innerHTML = '<option value="NULL">Loading...</option>';
    let req = new XMLHttpRequest();
    req.open("GET", "/limousine/public/call/api/crypto/search?symbol=" + formTypeValue, true);
    req.onload = getResponseCryptos;
    req.send();
}


function getResponseCryptos(){

    let datas = JSON.parse(this.responseText);


    let arrayCryptoInfo = [];

        for (let i = 0; i < datas.length; i++) {
            let array = [];

            array.push(datas[i].symbol)
            array.push(datas[i].name)
            array.push(datas[i].nameId)

            arrayCryptoInfo.push(array)
        }


    updateBaseInputCrypto(arrayCryptoInfo);

}


function updateBaseInputCrypto(arrayInfo){

    let inputResult = document.getElementById("row_result");

    inputResult.removeAttribute("readonly");
    inputResult.removeAttribute("disabled");


    inputResult.innerHTML = '<option value="NULL">CHOOSE ONE</option>'
    for(const key in arrayInfo){
        inputResult.innerHTML += '<option value="' + arrayInfo[key][0].toUpperCase() + '">' + arrayInfo[key][1] + ' | ' + arrayInfo[key][0].toUpperCase() + ' | ' + arrayInfo[key][2] + '</option>'
    }

    let rowSelect = document.getElementById("row_result");
    rowSelect.removeAttribute("oninput");
    rowSelect.setAttribute("oninput","hydrateOtherInputCrypto()");
}



function hydrateOtherInputCrypto(){
    //get value from input and make it an array
    let selectedStockOrEtf = document.getElementById("row_result");
    let infos = selectedStockOrEtf.options[selectedStockOrEtf.selectedIndex].text;
    let infoArray = infos.split(' | ');

    //now get input and hydrate then
    //let containerCurrency = document.getElementById("container-input-devise");
    //containerCurrency.innerHTML = '<input type="text" id="row_devise" name="row[devise]" required="required" class="form-control" readOnly="readonly">'
    //let formCurrency = document.getElementById("row_devise");
    //formCurrency.value = infoArray[3];


    let formSymbol = document.getElementById("row_symbol");

    let formName = document.getElementById("row_name");
    formName.removeAttribute("disabled");

    formSymbol.value = infoArray[1];

    getValueForThisSymbolCrypto(infoArray[2])
}


function getValueForThisSymbolCrypto(currentName){


    let inputValue = document.getElementById("row_value");
    let inputTotalValue = document.getElementById("row_totalValue");
    let inputQuantity = document.getElementById("row_number");

    //CLEAR INPUT BEFORE LOADING
    inputQuantity.value = "";
    inputValue.value = "";
    inputTotalValue.value = "";

    //LOADING VALUE
    inputValue.value = "Loading...";


    let req = new XMLHttpRequest();
    req.open("GET", "https://api.coingecko.com/api/v3/coins/" + currentName, true);
    req.onload = getCryptoPrice;
    req.send();
}

function getCryptoPrice(){

    if (typeof data == 'undefined'){
        data = JSON.parse(this.responseText);
    }


    let rowCurrency = document.getElementById("row_devise");
    rowCurrency.removeAttribute("disabled");
    let currencySelect = rowCurrency.value;

    let inputQuantity = document.getElementById("row_number");
    inputQuantity.removeAttribute("disabled");


    let thePrice = 0;
    console.log(currencySelect);

    if (currencySelect === "USD"){
        thePrice = data.market_data.current_price.usd;
    }else if(currencySelect === "EUR"){
        thePrice = data.market_data.current_price.eur;
    }else{
        thePrice = data.market_data.current_price.usd;
        rowCurrency.value = "USD";
    }

    thePrice = Number(thePrice).toFixed(2);


    rowCurrency.setAttribute("oninput","changeValue(); calcTotalValue()")

    updateValueInput(thePrice);
}

function changeValue(){
    getCryptoPrice();
}




/**
 *
 * @description: functions for Stocks and ETFs
 *
 **/

function updateBaseInput(arrayInfo){

    let inputResult = document.getElementById("row_result");

    inputResult.removeAttribute("readonly");
    inputResult.removeAttribute("disabled");


    inputResult.innerHTML = '<option value="NULL">CHOOSE ONE</option>'
    for(const key in arrayInfo){
        inputResult.innerHTML += '<option value="' + arrayInfo[key][0] + '">' + arrayInfo[key][1] + ' | ' + arrayInfo[key][0] + ' | ' + arrayInfo[key][2] + ' | ' + arrayInfo[key][3] + '</option>'
    }

    let rowSelect = document.getElementById("row_result");
    rowSelect.removeAttribute("oninput");
    rowSelect.setAttribute("oninput","hydrateOtherInput(this)");

}

function hydrateOtherInput(elem){
    //get value from input and make it an array
    let selectedStockOrEtf = document.getElementById("row_result");
    let infos = selectedStockOrEtf.options[selectedStockOrEtf.selectedIndex].text;
    let infoArray = infos.split(' | ');

    //now get input and hydrate then
    let containerCurrency = document.getElementById("container-input-devise");
    containerCurrency.innerHTML = '<input type="text" id="row_devise" name="row[devise]" required="required" class="form-control" readOnly="readonly">'
    let formCurrency = document.getElementById("row_devise");
    formCurrency.value = infoArray[3];


    let formSymbol = document.getElementById("row_symbol");

    let formName = document.getElementById("row_name");
    formName.removeAttribute("disabled");

    formSymbol.value = infoArray[1];

    let content = document.getElementById('row_result');
    let contentText = elem.options[elem.selectedIndex].text;
    let contentTile = contentText.split(' | ')

    let rowResultName = document.getElementById('row_resultName');
    rowResultName.value = contentTile[0];

    getValueForThisSymbol(infoArray[1])
}


function getValueForThisSymbol(currentSymbol){


    let inputValue = document.getElementById("row_value");
    let inputTotalValue = document.getElementById("row_totalValue");
    let inputQuantity = document.getElementById("row_number");

    //CLEAR INPUT BEFORE LOADING
    inputQuantity.value = "";
    inputValue.value = "";
    inputTotalValue.value = "";

    //LOADING VALUE
    inputValue.value = "Loading...";


    let req = new XMLHttpRequest();
    req.open("GET", "/limousine/public/call/api/stock&etf/price?symbol=" + currentSymbol, true);
    req.onload = geStockOrETFPrice;
    req.send();
}


function geStockOrETFPrice(){
    let inputQuantity = document.getElementById("row_number");
    inputQuantity.removeAttribute("disabled");

    let data = JSON.parse(this.responseText);
    let datas = data["Global Quote"];
    let thePrice = datas["05. price"];

    thePrice = Number(thePrice).toFixed(2);
    console.log(thePrice);


    updateValueInput(thePrice);
}

function updateValueInput(thePrice){
    let inputValue = document.getElementById("row_value");
    inputValue.value = thePrice;
    console.log(thePrice)
}


function test(){
    let inputNumber = document.getElementById("row_number");
    let inputValue = document.getElementById("row_value");
    let inputTotalValue = document.getElementById("row_totalValue");
    let inputDevise = document.getElementById("row_devise");
    let symboleInput = document.getElementById("row_symbol");
    let inputName = document.getElementById("row_name");

    console.log(inputNumber.value + " " + inputValue.value  + " " + inputTotalValue.value  + " " + inputDevise.value  + " " + symboleInput.value  + " " + inputName.value )
}