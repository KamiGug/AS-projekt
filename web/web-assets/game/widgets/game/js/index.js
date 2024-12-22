$(() => {
    roomFunctions.callRejoin();
})

let pageNumber = 0;

// todo: add function add error

const roomFunctions = {
    callRejoin: () => {
        $.ajax({
            url: '/game/room/rejoin',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                return roomFunctions.decideListOrJoinRoom(response)
            },
            error: () => {
                setTimeout(() => {
                    roomFunctions.callRejoin()
                }, 1000)
            },

        })
    },
    decideListOrJoinRoom: (response) => {
        if (response?.room === null) {
            return buildListFunctions.initList()
        } else {
            return gameFunctions.initGame(response.room)
        }
    },
    showLoader: () => {
        $('#game-wrapper .loader').show()
    },
    hideLoader: () => {
        $('#game-wrapper .loader').hide()
    },
    fillView: (view, args = {}) => {
        const handler = new Function('args', [
            'const tagged = ( ' + Object.keys(args).join(', ') + ' ) =>',
            '`' + view + '`',
            'return tagged(...Object.values(args))'
        ].join('\n'))
        return handler(args)
    },
}
