$(() => {
    roomFunctions.init();
})

let pageNumber = 0;

// todo: add function add error
const loader = '<div class="loader"></div>'

const roomFunctions = {
    init: () => {
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
                    roomFunctions.init()
                }, 1000)
            },

        })
    },
    decideListOrJoinRoom: (response) => {
        if (response?.room === null) {
            return buildListFunctions.initList(false)
        } else {
            return gameFunctions.initGame(response.room, false)
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
    resetGameWrapper: () => {
        const gameWrapper = $('#game-wrapper');
        gameWrapper.empty();
        gameWrapper.append($(loader));
    }
}
