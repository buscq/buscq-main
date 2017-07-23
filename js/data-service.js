var dataService = (() => {

    function getBusLines(callback) {
	callback(lineas);
    }

    return {
        getBusLines: (callback) => {getBusLines(callback)},
    }
})()
