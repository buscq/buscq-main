var busLinesComponent = (() => {
    
    this.busLinesContainer = undefined;
    this.busLines = undefined;
    
    this.init = () => {
        busLines = [];
        busLinesContainer = $('#bus-lines');

        dataService.getBusLines((data, status) => {
            busLines = data;
            updateAndDisplayBusLines(busLines);
        });
    };

    this.isSearchTile = (busLine) => {
        return busLine.search !== undefined
    }
    
    this.updateAndDisplayBusLines = (busLines) => {
        $(busLinesContainer).children('.bus-line').remove();

        for (let i = 0; i < busLines.length; i++) {
            
            let busLineElement = $('<div class="bus-line"></div>')
            .html(
                `<div class="wrapper">
                    <div class="content">
			<a href="lineas/` + busLines[i].id + `">
                        	<div class="number"></div>
                        	<div class="name"></div>
			</a>
                    </div>
                </div>`);
            $(busLineElement).hide();
            
            let number = $(busLineElement).find('.number');
            let name = $(busLineElement).find('.name');
            let wrapper = $(busLineElement).find('.wrapper');
            $(number).text(busLines[i].sinoptico);
            $(name).text(busLines[i].nombre);
            
            let intervalId;
            $(wrapper).mouseenter(() => {
                if (isSearchTile(busLines[i])) {
                    $(number).stop(true, true);
                    $(number).slideDown(250);
                } else {
                    $(name).stop(true, true);
                    $(name).slideDown(250);
                    $(number).slideUp(250);
                }
                intervalId = setInterval(() => {
                    $(name).slideToggle(1000);
                    $(number).slideToggle(1000);
                }, 3000);
            });
            $(wrapper).mouseleave(() => {
                if (isSearchTile(busLines[i])) {
                    $(number).slideUp(250);
                } else {
                    $(name).slideUp(250);
                    $(number).stop(true, true);
                    $(number).slideDown(250);
                }
                clearInterval(intervalId);
            });
            $(wrapper).click(() => {
                toggleBusLineDetail(busLines[i]);
            });

            $(name).hide();
            $(wrapper).css('background', busLines[i].estilo);

            $(busLinesContainer).append(busLineElement);

            $(busLineElement).fadeIn('slow');
        }
    }
    
    return {
        init: () => {init();}
    };
})();
