$(() => {
    roomFunctions.callRejoin();
    // setInterval(() => {
    //     if ($('#game-wrapper .loader').is(":visible")) {
    //         roomFunctions.hideLoader()
    //     } else {
    //         roomFunctions.showLoader()
    //     }
    // }, 5000)

})

let pageNumber = 0;

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
            return roomFunctions.getRoom(response.room)
        }
    },
    showLoader: () => {
        $('#game-wrapper .loader').show()
    },
    hideLoader: () => {
        $('#game-wrapper .loader').hide()
    },
    // getRoom: (id) => {
    //
    // }
}
