const listTemplates = {}
const listVars = {
    lastRefresh: null,
    itemCount: 10,
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
        let view = listTemplates.listTemplate;
        let newView;
        while (true) {
            newView = roomFunctions.fillView(view, listTemplates);
            if (view === newView) break;
            view = newView;
        }
        $(view).appendTo($('#game-wrapper'));
        $('#room-search-btn').on('click', (e) => {
            buildListFunctions.sendChangeList(e);
        })

        //todo: add event listener to the refresh button
        $('#room-search-btn').on('click', (e) => {
            buildListFunctions.refreshList(e);
        })

        buildListFunctions.initialFillList();
        roomFunctions.hideLoader();
    },
    fillList: (rooms) => {
        buildListFunctions.clearList();
        const listWrapper = $('#room-list-wrapper');
        if (rooms.length < 1) {
            $(listTemplates.emptyMessage ?? '<div>').appendTo(listWrapper);
            return;
        }
        for (const room of rooms) {
            buildListFunctions.appendListenerToListElement(
                $(roomFunctions.fillView(listTemplates.elementPartial, room)).appendTo(listWrapper)
            );
        }
    },
    appendListenerToListElement: (element) => {
        element.on('click', (e) => {
            const wrapper = $(e.target).closest('.room-list-element')
            gameFunctions.joinGame(wrapper.data('id'));
        })
    },
    initialFillList: () => {
        $.ajax({
            url: '/game/room/list',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({
                pageNumber: listVars.currentPage,
                itemCount: listVars.itemCount,
                timestamp: listVars.lastRefresh,
                sortOrder: null,
            }),
            success: function(response) {
                listVars.timestamp = response.timestamp
                buildListFunctions.fillVars(response)
                buildListFunctions.fillList(response.rooms)
                buildListFunctions.setUpPagination()
            },
            error: () => {
                setTimeout(() => {
                    buildListFunctions.initialFillList();
                }, 1000)
            },
        });
    },
    sendChangeList: (e = null) => {
        e?.preventDefault();
        roomFunctions.showLoader();
        const form = $('#room-search-form');
        form.append(`<input id="soon-to-be-removed-hidden-field-for-timestamp type="hidden" name="timestamp" value="${listVars.timestamp ?? null}"` )
        const formData = form.serialize();
        $('#soon-to-be-removed-hidden-field-for-timestamp').remove()

        const urlSearchParams = new URLSearchParams();
        if (listVars.itemCount != null) urlSearchParams.append('count', listVars.itemCount);
        if (listVars.currentPage != null) urlSearchParams.append('page', listVars.currentPage);
        $.ajax({
            url: "/game/room/list?" + urlSearchParams.toString(),
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                    listVars.timestamp = response.timestamp
                    buildListFunctions.fillVars(response)
                    buildListFunctions.fillList(response.rooms)
                    buildListFunctions.setUpPagination()
                    roomFunctions.hideLoader();
            },
            error: function() {
            }
        });
    },
    fillVars: (response) => {
        listVars.currentPage = response.page;
        listVars.timestamp = response.timestamp;
        listVars.pageCount = response.pageCount
    },
    // todo: finish setUpPagination
    setUpPagination: () => {
        console.log('todo: finish setUpPagination')
        console.log('currentPage', 'currentPage')
        console.log('pageCount', 'pageCount')
    },
    clearList: () => {
        $('#room-list-wrapper').empty();
    },
    refreshList: (e) => {
        e?.preventDefault();
        roomFunctions.showLoader();
        listVars.lastRefresh = null;
        buildListFunctions.sendChangeList()
        roomFunctions.hideLoader();
    }
}
