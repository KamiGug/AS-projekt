const buildListFunctions = {
    initList: () => {
        $.ajax({
            url: '/game/room/init-list',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({type: 'list'}),
            success: function(response) {
                buildListFunctions.buildList(response)
            },
            error: () => {
                setTimeout(() => {
                    buildListFunctions.initList();
                }, 1000)
            },
        })
    },
    buildList: (response) => {
        console.log('in Build');
        // $('#game-wrapper').add('div').html(response?.template);
        // outterWrapper.add(response?.template);
        const listTemplate = $(response?.listTemplate ?? '<div>').appendTo($('#game-wrapper'));
        // $('#game-wrapper').append($(response?.listTemplate ?? '<div>'))

        //$('#room-list-template#room-list-bar').append($(response?.listBar ?? '<div>'))
        listTemplate.children('#room-list-bar').replaceWith($(response?.listBar ?? '<div>'));
        const listWrapper = listTemplate.children('#room-list-wrapper').replaceWith($(response?.listPartial ?? '<div>'));
        buildListFunctions.fillList(listWrapper);
        roomFunctions.hideLoader();
    },
    fillList: (root, page = 0, itemCount = 25) => {
        $.ajax({
            url: '/game/room/list',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify({
                pageNumber: page,
                itemCount: itemCount,
            }),
            success: function(response) {
                buildListFunctions.buildList(response)
            },
            error: () => {
                setTimeout(() => {
                    buildListFunctions.initList();
                }, 1000)
            },
        })
    },
    clearList: () => {
        $('#room-list-wrapper').empty();
    },
}
