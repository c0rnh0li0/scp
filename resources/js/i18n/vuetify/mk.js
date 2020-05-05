// @ts-ignore
import { en } from 'vuetify/lib/locale/en'

export default {
    ...en,
    badge: 'Значка',
    close: 'Затвори',
    dataIterator: {
        noResultsText: 'Не се пронајдени резултати',
        loadingText: 'Се вчитува...',
    },
    dataTable: {
        itemsPerPageText: 'Записи по страна:',
        ariaLabel: {
            sortDescending: 'По опаѓачки редослед.',
            sortAscending: 'По растечки редослед.',
            sortNone: 'Без подредување.',
            activateNone: 'Активирај за да нема подредување.',
            activateDescending: 'Активирај за опаѓачко подредување.',
            activateAscending: 'Активирај за растечко подредување.',
        },
        sortBy: 'Подреди по',
    },
    dataFooter: {
        itemsPerPageText: 'Записи по страна:',
        itemsPerPageAll: 'Сите',
        nextPage: 'Следна страна',
        prevPage: 'Претходна страна',
        firstPage: 'Прва страна',
        lastPage: 'Последна страна',
        pageText: '{0}-{1} од {2}',
    },
    datePicker: {
        itemsSelected: '{0} селектирани',
    },
    noDataText: 'Нема записи',
    carousel: {
        prev: 'Претходна',
        next: 'Следна',
        ariaLabel: {
            delimiter: 'Слајд {0} од {1}',
        },
    },
    calendar: {
        moreEvents: 'Уште {0}',
    },
    fileInput: {
        counter: '{0} датотеки',
        counterSize: '{0} датотеки ({1} вкупно)',
    },
    timePicker: {
        am: 'Претпладне',
        pm: 'После пладне',
    }
}