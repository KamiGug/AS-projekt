const listTemplates = {}
const listVars = {
    lastRefresh: null,
    itemCount: 25,
    currentPage: 0,
}

const buildListFunctions = {
    // todo: add some sort of reading into listVars.itemCount
    initList: (resetGameWrapper = true) => {
        if (resetGameWrapper) {
            roomFunctions.resetGameWrapper();
        }
        $.ajax({
            url: '/game/room/init-list',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({type: 'list'}),
            success: function(response) {
                buildListFunctions.fillListTemplates(response);
                buildListFunctions.buildList()
            },
            error: () => {
                setTimeout(() => {
                    buildListFunctions.initList(false);
                }, 1000)
            },
        })
    },
    fillListTemplates: (response) => {
        listTemplates.listTemplate = response?.listTemplate;
        listTemplates.listBar = response?.listBar;
        listTemplates.listPartial = response?.listPartial;
        listTemplates.elementPartial = response?.elementPartial;
        listTemplates.emptyMessage = response?.emptyMessage;
        listTemplates.listFooter = response?.listFooter;
    },
    buildList: () => {
        const listTemplate = $(listTemplates.listTemplate ?? '<div>').appendTo($('#game-wrapper'));
        listTemplate.children('#room-list-bar').replaceWith($(listTemplates?.listBar ?? '<div>'));
        listTemplate.children('#room-list-wrapper').replaceWith($(listTemplates?.listPartial ?? '<div>'));
        listTemplate.children('#room-list-footer').replaceWith($(listTemplates?.listFooter ?? '<div>'));
        buildListFunctions.fillList();
        roomFunctions.hideLoader();
    },
    fillList: (page = 0) => {
        $.ajax({
            url: '/game/room/list',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({
                pageNumber: page,
                itemCount: listVars.itemCount,
                timestamp: listVars.lastRefresh,
                sortOrder: null,
            }),
            success: function(response) {
                buildListFunctions.clearList();
                const listWrapper = $('#room-list-wrapper');
                if (response.rooms.length < 1) {
                    $(listTemplates.emptyMessage ?? '<div>').appendTo(listWrapper);
                    return;
                }
                buildListFunctions.setUpPagination(response.page, response.pageCount)
                for (const room of response.rooms) {
                    $(roomFunctions.fillView(listTemplates.elementPartial, room))
                        .appendTo(listWrapper)
                        .on('click', (e) => {
                            gameFunctions.joinGame(e.target.dataset?.id);
                        });
                }
                // use the listVars.listWrapper
                // buildListFunctions.buildList(response)
            },
            error: () => {
                setTimeout(() => {
                    buildListFunctions.fillList(page);
                }, 1000)
            },
        });
    },
    // todo: finish setUpPagination
    setUpPagination: (pageNumber, pageCount) => {
        console.log('todo: finish setUpPagination')
        console.log('pageNumber', pageNumber)
        console.log('pageCount', pageCount)
    },
    clearList: () => {
        $('#room-list-wrapper').empty();
    },
    refreshList: () => {
        roomFunctions.showLoader();
        listVars.lastRefresh = null;
        buildListFunctions.fillList()
        roomFunctions.hideLoader();
    }
}
