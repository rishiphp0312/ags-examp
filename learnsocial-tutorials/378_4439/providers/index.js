/*
 * Angular.JS definitions
 */
// app.config(["$provide", "sampleProvider", "testTextCnst", "testTextVal", configFunc]);
var app = angular.module("myApp", [])
.config(["$provide", "sampleProvider", "testTextCnst", configFunc])
.factory("collection", collectionFactory)
.provider("sample", collectionProviderByProvide2)
.constant('testTextCnst', 'test text in constant')
.value('testTextVal', 'test text in value')
.controller("mainCtrl", ["$scope", "testTextVal", "collection", "collectionByProvide", "collectionByProvideProvider", "sample","testTextCnst", mainCtrlFunc]);


/*
 * Javascript business logic
 */
// function configFunc($provide, sampleProvider, testTextCnst,testTextVal) {
function configFunc($provide, sampleProvider, testTextCnst) {
// var configFunc = function ($provide, sampleProvider, testTextCnst) {
    $provide.factory("collectionByProvide", collectionFactoryByProvide);
    $provide.provider("collectionByProvideProvider", collectionProviderByProvide);
    console.log(testTextCnst);
    testTextCnst =123;
	console.log('atya=='+testTextCnst);
	// testTextVal =123444;
	//console.log('atyaar=='+testTextVal);
	
	//testTextVal
   // sampleProvider.setValue("... And this is some more text...");
};

function mainCtrlFunc(s, t, c, cp, cpp, samp,cnt) {
    console.log('check-values'+cpp.someText);
	console.log('check-testTextVal'+t);
		console.log('check-testTextVal'+cnt);

	
	
    s.someText = samp.someText;
    // s.someText4 = cpp.someText;
    // s.someText3 = cp.someText;
    // s.someText = t;
    s.someText2 = c.someText;
    s.someText1 = "This is some text";

};

function collectionFactory() {
    return {
        someText: "rishi 4This is some text from collectionFactory"
    }
};

function collectionFactoryByProvide() {
    return {
        someText: "rishi 3 This is some text from collectionFactoryByProvide"
    }
};

function collectionProviderByProvide() {
    return {
        $get: function () {
            return {
                someText: "rishi 2 This is some text from collectionProviderByProvide"
            }
        }
    }
};

function collectionProviderByProvide2() {
    var moreText = "...Init Text...";
    return {
        setValue: function (value) {
            moreText=value
        },
        $get: function () {
            return {
                someText: "rishi This is some text from collectionProviderByProvide" + moreText
            }
        }
    }
};

