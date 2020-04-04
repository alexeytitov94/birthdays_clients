new Vue({
    el: '#app',
    data: {
        new_portal: 'no',
        clients_dr: [],
        modal: false,
        question_form: {
            name: '',
            email: '',
            phone: '',
            text: ''
        },
        settings: {
            responsible: {
                assigned: true,
                personal: false
            },
            information: {
                task: false,
                chat: true,
                contact: false
            },
            day_information: {
                today: false,
                day: true,
                myday: false
            }
        },
        chose_user: {
            name: '',
            id: ''
        },
        enter_day: 0
    },
    methods: {
        fuckingSizeWindow() {

            var body = document.body;
            var html = document.documentElement;

            var height = Math.max(body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight);

            var width = Math.max(body.scrollWidth, body.offsetWidth,
                html.clientWidth, html.scrollWidth, html.offsetWidth);

            BX24.resizeWindow(width, document.body.offsetHeight +140)
        },
        setting_c(type_setting, block_settings) {

            var ctx = this;

            switch (type_setting) {
                case 'responsible':
                    if (block_settings == 'assigned') {
                        ctx.settings.responsible.assigned = true;
                        ctx.settings.responsible.personal = false;

                        ctx.chose_user.name = '';
                        ctx.chose_user.id = '';

                        ctx.sendUpdateField('ASSIGNED', 'ASSIGNED')

                    } else {

                        ctx.selectUser();

                        ctx.settings.responsible.assigned = false;
                        ctx.settings.responsible.personal = true;

                    }
                    break;
                case 'information':
                    if (block_settings == 'task') {

                        ctx.settings.information.task = true;
                        ctx.settings.information.chat = false;
                        ctx.settings.information.contact = false;

                        ctx.sendUpdateField('TYPE_NOTIFY', 'TASK')

                    } else if (block_settings == 'chat') {

                        ctx.settings.information.task = false;
                        ctx.settings.information.chat = true;
                        ctx.settings.information.contact = false;

                        ctx.sendUpdateField('TYPE_NOTIFY', 'CHAT')

                    } else {

                        ctx.settings.information.task = false;
                        ctx.settings.information.chat = false;
                        ctx.settings.information.contact = true;

                        ctx.sendUpdateField('TYPE_NOTIFY', 'DELO')
                    }
                    break;
                case 'day_information':
                    if (block_settings == 'today') {

                        ctx.settings.day_information.today = true;
                        ctx.settings.day_information.day = false;
                        ctx.settings.day_information.myday = false;

                        ctx.sendUpdateField('DATA_NOTIFY', 0)

                    } else if (block_settings == 'day') {

                        ctx.settings.day_information.today = false;
                        ctx.settings.day_information.day = true;
                        ctx.settings.day_information.myday = false;

                        ctx.sendUpdateField('DATA_NOTIFY', 1)

                    } else {

                        ctx.settings.day_information.today = false;
                        ctx.settings.day_information.day = false;
                        ctx.settings.day_information.myday = true;

                    }
                    break;
            }

        },
        day_to_bd(name) {

            var date1 = new Date(name);
            date1.setFullYear(2020);
            console.log(date1)

            var date2 = new Date();
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());

            var sign = Math.sign(date1 - date2 );

            if (sign == -1) {
                var diffDays = 366 - Math.round(timeDiff / (1000 * 3600 * 24));
            } else {
                var diffDays = Math.round(timeDiff / (1000 * 3600 * 24));
            }


            return diffDays;
        },
        mounth_bd(name) {

            switch (name) {
                case 0:
                    return 'Январь'
                    break;
                case 1:
                    return 'Февраль'
                    break;
                case 2:
                    return 'Март'
                    break;
                case 3:
                    return 'Апрель'
                    break;
                case 4:
                    return 'Май'
                    break;
                case 5:
                    return 'Июнь'
                    break;
                case 6:
                    return 'Июль'
                    break;
                case 7:
                    return 'Август'
                    break;
                case 8:
                    return 'Сентябрь'
                    break;
                case 9:
                    return 'Октябрь'
                    break;
                case 10:
                    return 'Ноябрь'
                    break;
                case 11:
                    return 'Декабрь'
                    break;
            }
        },
        getCurrentSeting() {

            var ctx = this;

            const data = new FormData();
            data.append('DATA', JSON.stringify(request));

            axios.post('https://b24apps.ru/local/b24apps/our_app/birthday/php/savePortal.php', data)
            .then(response => {

                if (response.data == 'new') ctx.new_portal = 'new';

                //ASSIGNED
                if (response.data.ASSIGNED == 'ASSIGNED') {
                    ctx.settings.responsible.assigned = true;
                    ctx.settings.responsible.personal = false;
                } else {

                    BX24.callMethod('user.get', {"ID": response.data.ASSIGNED}, function (response) {

                        if (response.answer.result.length) {

                            ctx.chose_user.name = response.answer.result[0].NAME
                            ctx.chose_user.name = " " + response.answer.result[0].LAST_NAME
                            ctx.chose_user.id = response.answer.result[0].ID

                            ctx.settings.responsible.assigned = false;
                            ctx.settings.responsible.personal = true;

                        }

                    });

                }

                //INFORMATION
                if (response.data.TYPE_NOTIFY == 'TASK') {

                    ctx.settings.information.task = true;
                    ctx.settings.information.chat = false;
                    ctx.settings.information.contact = false;

                } else if (response.data.TYPE_NOTIFY == 'CHAT' || response.data == 'new') {

                    ctx.settings.information.task = false;
                    ctx.settings.information.chat = true;
                    ctx.settings.information.contact = false;

                } else {

                    ctx.settings.information.task = false;
                    ctx.settings.information.chat = false;
                    ctx.settings.information.contact = true;

                }


                //DATE
                if (response.data.DATA_NOTIFY == '0') {

                    ctx.settings.day_information.today = true;
                    ctx.settings.day_information.day = false;
                    ctx.settings.day_information.myday = false;


                } else if (response.data.DATA_NOTIFY == '1' || response.data == 'new') {

                    ctx.settings.day_information.today = false;
                    ctx.settings.day_information.day = true;
                    ctx.settings.day_information.myday = false;

                } else {

                    ctx.enter_day = response.data.DATA_NOTIFY;

                    ctx.settings.day_information.today = false;
                    ctx.settings.day_information.day = false;
                    ctx.settings.day_information.myday = true;

                }



            })
            .then(response => {

                var body = document.body;
                var html = document.documentElement;

                var height = Math.max(body.scrollHeight, body.offsetHeight,
                    html.clientHeight, html.scrollHeight, html.offsetHeight);

                var width = Math.max(body.scrollWidth, body.offsetWidth,
                    html.clientWidth, html.scrollWidth, html.offsetWidth);


                BX24.resizeWindow(width, height + 50)
            })


            BX24.callMethod('user.current', {}, function(res){

                ctx.question_form.name = res.data().NAME;
                ctx.question_form.phone = res.data().PERSONAL_MOBILE;
                ctx.question_form.email = res.data().EMAIL;

                if (ctx.new_portal == 'new') {
                    ctx.newPortal();
                }

            });

        },
        getClients() {

            var ctx = this;
            BX24.callMethod(
                "crm.contact.list",
                {
                    order: { "BIRTHDATE": "ASC" },
                    filter: {'!BIRTHDATE':''},
                    select: ['BIRTHDATE', 'NAME', 'LAST_NAME']
                },
                function(result)
                {
                    ctx.clients_dr = result.data().map(function (item, index, array) {
                        return {
                            'NAME': item.NAME + ' ' + item.LAST_NAME,
                            'BIRTHDATE': new Date(item.BIRTHDATE).getDate() + " " + ctx.mounth_bd(new Date(item.BIRTHDATE).getMonth()),
                            'DAY': ctx.day_to_bd(item.BIRTHDATE)
                        }
                    })

                    //ctx.clients_dr = ctx.clients_dr.slice(0, 7)
                    ctx.clients_dr = ctx.clients_dr;
                    ctx.fuckingSizeWindow()

                }
            );
        },
        selectUser() {
            var ctx = this;

            BX24.selectUser(function (user) {
                ctx.chose_user.name = user.name
                ctx.chose_user.id = user.id

                ctx.sendUpdateField('ASSIGNED', user.id)
            })

        },
        sendUpdateField(field, value){

            var ctx = this;


            const data = new FormData();
            data.append('portal', request.DOMAIN);
            data.append('field', field);
            data.append('value', value);

            axios.post('https://b24apps.ru/local/b24apps/our_app/birthday/php/save_filed.php', data)
            .then(response => {});

        },
        dayCount(count) {
            var res = parseInt(this.enter_day) + count;

            if (res <= 0) {
                this.enter_day = 0;
            } else {
                this.enter_day = res;
            }

            this.sendUpdateField('DATA_NOTIFY', this.enter_day)
        },
        newPortal() {
            var ctx = this;

            const data = new FormData();
            data.append('data', JSON.stringify(this.question_form));
            data.append('type', 'new');
            data.append('portal', request.DOMAIN);


            axios({
                url: 'https://b24apps.ru/local/b24apps/our_app/scripts_for_all_apps/workPortal/lead.php',
                method: 'POST',
                data: data
            }).then((response) => {
                ctx.modal = false
            });
        },
        sendRequest() {
            var ctx = this;

            const data = new FormData();
            data.append('data', JSON.stringify(this.question_form));
            data.append('type', 'request');
            data.append('portal', request.DOMAIN);


            axios({
                url: 'https://b24apps.ru/local/b24apps/our_app/scripts_for_all_apps/workPortal/lead.php',
                method: 'POST',
                data: data
            }).then((response) => {
                ctx.modal = false
            });
        }
    },
    computed: {
      sortedClients() {
          return this.clients_dr.sort((a, b) => a.DAY - b.DAY );
      }
    },
    created() {
        this.getCurrentSeting();
        this.getClients();
    }
})
