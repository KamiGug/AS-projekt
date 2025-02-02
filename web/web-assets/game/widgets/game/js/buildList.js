const listTemplates = {}
const listVars = {
    timestamp: null,
    itemCount: 10,
    currentPage: 0,
}
const allPagesRoomListLimit = 6;
const numberOfPagesOnASideOfActive = 2;


const buildListFunctions = {
    // todo: add some sort of reading into listVars.itemCount
    initList: (resetGameWrapper = true) => {
        if (resetGameWrapper) {
            roomFunctions.resetGameWrapper();
        }
        listVars.timestamp = null;
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
        listTemplates.paginationElementEnabled = response?.paginationElementEnabled;
        listTemplates.paginationElementDisabled = response?.paginationElementDisabled;
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
        $('#room-search-refresh-btn').on('click', (e) => {
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
        console.log('rooms', rooms)
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
                timestamp: listVars.timestamp,
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
        console.log('listVars.timestamp', listVars.timestamp)
        $('<input id="soon-to-be-removed-hidden-field-for-timestamp" type="hidden" name="RoomSearch[timestamp]" value="'
            + (listVars.timestamp ?? null)
            + '">' )
            .appendTo(form)
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
    setUpPagination: () => {
        const root = $('#room-list-footer');
        root.empty();
        const templateValueObject = {
            symbol: '<<',
            number: '1',
            flexorder: '-2',
        }

        buildListFunctions.drawPaginationElement(root, templateValueObject, listVars.currentPage !== 1);
        templateValueObject.symbol = '<';
        templateValueObject.number = listVars.currentPage - 1;
        templateValueObject.flexorder = -1
        buildListFunctions.drawPaginationElement(root, templateValueObject, listVars.currentPage !== 1);

        if (listVars.pageCount > allPagesRoomListLimit) {
            for (let i = 1; i <= numberOfPagesOnASideOfActive; i++) {
                if (listVars.currentPage - i <= 0) {
                    break;
                }
                const number = listVars.currentPage - i;
                templateValueObject.symbol = number;
                templateValueObject.number = number;
                templateValueObject.flexorder = number;
                buildListFunctions.drawPaginationElement(root, templateValueObject, true);
            }
            templateValueObject.symbol = listVars.currentPage;
            templateValueObject.number = listVars.currentPage;
            templateValueObject.flexorder = listVars.currentPage;
            buildListFunctions.drawPaginationElement(root, templateValueObject, true).addClass('active');
            console.log('listVars.currentPage', listVars.currentPage)
            console.log('listVars.pageCount', listVars.pageCount)
            for (let i = 1; i <= numberOfPagesOnASideOfActive; i++) {
                console.log('listVars.currentPage + i', listVars.currentPage + i)
                console.log('listVars.pageCount', listVars.pageCount)
                if (listVars.currentPage + i >= listVars.pageCount) {
                    break;
                }
                const number = listVars.currentPage + i;
                templateValueObject.symbol = number;
                templateValueObject.number = number;
                templateValueObject.flexorder = number;
                buildListFunctions.drawPaginationElement(root, templateValueObject, true);
            }
        } else {
            for (let i = 1; i < listVars.currentPage; i++) {
                console.log('i');
                templateValueObject.symbol = i;
                templateValueObject.number = i;
                templateValueObject.flexorder = i;
                buildListFunctions.drawPaginationElement(root, templateValueObject, true);
            }
            templateValueObject.symbol = listVars.currentPage;
            templateValueObject.number = listVars.currentPage;
            templateValueObject.flexorder = listVars.currentPage;
            buildListFunctions.drawPaginationElement(root, templateValueObject, true).addClass('active');
            for (let i = listVars.currentPage + 1; i <= listVars.pageCount; i++) {
                templateValueObject.symbol = i;
                templateValueObject.number = i;
                templateValueObject.flexorder = i;
                buildListFunctions.drawPaginationElement(root, templateValueObject, true);
            }
        }
        templateValueObject.symbol = '>';
        templateValueObject.number = listVars.currentPage + 1;
        templateValueObject.flexorder = listVars.pageCount + 1
        buildListFunctions.drawPaginationElement(root, templateValueObject, listVars.currentPage !== listVars.pageCount);
        templateValueObject.symbol = '>>';
        templateValueObject.number = listVars.pageCount;
        templateValueObject.flexorder = listVars.pageCount + 2;
        buildListFunctions.drawPaginationElement(root, templateValueObject, listVars.currentPage !== listVars.pageCount);

        templateValueObject.symbol = '/' + listVars.pageCount;
        templateValueObject.number = 0;
        templateValueObject.flexorder = listVars.pageCount + 3;
        buildListFunctions.drawPaginationElement(root, templateValueObject, false);

        $('#room-list-footer>li>a').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const newPage = $(e.target).data('page')
            console.log(newPage);
            listVars.currentPage = newPage;
            buildListFunctions.sendChangeList();
        });

        root.children().each

    },
    drawPaginationElement: (root, templateValueObject, isEnabled) => {
        if (isEnabled) {
            return $(roomFunctions.fillView(listTemplates.paginationElementEnabled, templateValueObject)).appendTo(root);
        } else {
            return $(roomFunctions.fillView(listTemplates.paginationElementDisabled, templateValueObject)).appendTo(root);
        }
    },
    clearList: () => {
        $('#room-list-wrapper').empty();
    },
    refreshList: (e) => {
        e?.preventDefault();
        listVars.timestamp = null;
        roomFunctions.showLoader();
        buildListFunctions.sendChangeList()
        roomFunctions.hideLoader();
    }
}
