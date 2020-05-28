export default {
    message: {
        homepage: {
            welcome_text: 'Добредојдовте на',
            about_us_title: 'За нас',
            about_us_text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae porttitor ex. Aliquam erat volutpat. Morbi vitae tristique purus. Nunc dapibus sem at feugiat finibus. Etiam euismod sapien diam. Nullam tincidunt maximus quam porta hendrerit. Integer vel tempus nulla. Aenean sed sem facilisis, dignissim sem at, elementum neque. Mauris eu justo at sapien efficitur tempus.',
            features_title: 'SCP можности',
            blog_title: 'Блог',
            contact_us_title: 'Контактирајте не',
        },
        menus: {
            admin: {
                menu: {
                    items: {
                        home: 'Почетна',
                        settings: 'Подесувања',
                        lookups: 'Предефинирани податоци',
                        lookup_items: {
                            contract_lengths: 'Времетраење на договори',
                            languages: 'Јазици',
                            user_types: 'Типови на корисници',
                            categories: 'Категории',
                            countries: 'Држави',
                            cities: 'Градови',
                            genders: 'Полови',
                            valutes: 'Валути',
                        },
                        contracts: 'Договори',
                        places: 'Бизниси',
                        people: 'Туристи',
                        offers: 'Понуди',
                        tickets: 'Билети',
                        blog: 'Блог',
                        tokens: 'Токени',
                        about: 'За SCP',
                        logout: 'Одјава',
                    }
                }
            },
            place: {
                menu: {
                    items: {
                        home: 'Почетна',
                        scan: 'Скенирање',
                        profile: 'Профил',
                        offers: 'Понуди',
                        profile_preview: 'Преглед на профил',
                        about: 'За SCP',
                        logout: 'Одјава',
                    }
                }
            },
            tourist: {}
        },
        widgets: {
            todaytickets: {
                today_text: 'Билети купени денес',
                week_text: 'Оваа недела',
                month_text: 'Овој месец'
            },
            dailyvisitors: {
                today_text: 'Посетители денес',
                week_text: 'Оваа недела',
                month_text: 'Овој месец'
            },
            dailynewusers: {
                today_text: 'Нови туристи денес',
                week_text: 'Оваа недела',
                month_text: 'Овој месец'
            },
            todayplaces: {
                today_text: 'Денешни посети',
                week_text: 'Оваа недела',
                month_text: 'Овој месец'
            },
            mostvisitedplaces: {
                title: 'Најпосетувани места',
                header_place: 'Бизнис',
                header_visits: 'Број на посети',
                footer: 'Најпосетувани места на сите времиња'
            },
            monthlytickets: {
                title: 'Билети за овој месец',
                total_tickets_lbl: 'Вкупно билети',
                used_tickets_lbl: 'Искористени билети',
                footer: 'Вкупен број на продадени и искористени билети за тековниот месец'
            },
            yearlyvisitors: {
                title: 'Посетители за оваа година',
                total_visitors_lbl: 'Вкупно посетители',
                footer: 'Вкупен број на посетители за тековната година'
            },
            yearlytickets: {
                title: 'Билети за оваа година',
                total_tickets_lbl: 'Вкупно билети',
                used_tickets_lbl: 'Искористени билети',
                footer: 'Вкупен број на продадени и искористени билети за тековната година'
            },
        },
        sections: {
            settings: {
                settings_title: 'Подесувања',
                settings_subtitle: 'Глобални прилагодувања за апликацијата',
                labels: {
                    site_name: 'Име на апликацијата',
                    site_logo: 'Лого',
                    frontpage_template: 'Темплејт за почетната страна',
                    contact_phone: 'Телефон за контакт',
                    contact_email: 'е-Пошта за контакт',
                    contact_address: 'Адреса за контакт',
                    default_language: 'Основен јазик',
                    last_updated: 'Последна промена',
                    contract_check: 'Проверка на договор',
                    contract_check_hint: 'Дали да постои проверка дали бизнис корисниците имаат валидни договори?',
                }
            },
            lookups: {
                contract_lengths: {
                    section_title: 'Времетраење на договори',
                    btn_new_title: 'Нов запис',
                    headers: {
                        name: 'Назив',
                        duration: 'Времетраење (месеци)',
                        price: 'Цена'
                    },
                    form: {
                        form_title_new: 'Нов запис',
                        form_title_edit: 'Измена на запис',
                        fields: {
                            name: 'Назив',
                            duration: 'Времетраење',
                            price: 'Цена',
                            valute: 'Валута'
                        }
                    },
                    durations: {
                        months_1: 'Еден месец',
                        months_3: 'Три месеци',
                        months_6: 'Шест месеци',
                        months_12: 'Една година',
                        months_24: 'Две години',
                        months_36: 'Три години',
                    }
                },
                languages: {
                    section_title: 'Јазици',
                    btn_new_title: 'Нов јазик',
                    headers: {
                        name: 'Име',
                        locale: 'Локал',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нов јазик',
                        form_title_edit: 'Измена на јазик',
                        fields: {
                            name: 'Име',
                            locale: 'Локал'
                        }
                    },
                },
                user_types: {
                    section_title: 'Типови на корисници',
                    btn_new_title: 'Нов тип на корисник',
                    headers: {
                        name: 'Назив',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нов тип на корисник',
                        form_title_edit: 'Измена на тип на корисник',
                        fields: {
                            name: 'Назив'
                        }
                    },
                },
                categories: {
                    section_title: 'Категории',
                    btn_new_title: 'Нова категорија',
                    headers: {
                        name: 'Име',
                        parent: 'Категорија родител',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нова категорија',
                        form_title_edit: 'Измена на категорија',
                        fields: {
                            name: 'Име',
                            parent: 'Категорија родител',
                        }
                    },
                },
                countries: {
                    section_title: 'Држави',
                    btn_new_title: 'Нова држава',
                    headers: {
                        name: 'Име',
                        code: 'Код',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нова држава',
                        form_title_edit: 'Измена на држава',
                        fields: {
                            name: 'Име',
                            code: 'Код',
                        }
                    },
                },
                cities: {
                    section_title: 'Градови',
                    btn_new_title: 'Нов град',
                    headers: {
                        name: 'Име',
                        country: 'Држава',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нов град',
                        form_title_edit: 'Измена на град',
                        fields: {
                            name: 'Име',
                            country: 'Држава'
                        }
                    },
                },
                genders: {
                    section_title: 'Полови',
                    btn_new_title: 'Нов пол',
                    headers: {
                        name: 'Назив',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нов пол',
                        form_title_edit: 'Измена на пол',
                        fields: {
                            name: 'Назив',
                        }
                    },
                },
                valutes: {
                    section_title: 'Валути',
                    btn_new_title: 'Нова валута',
                    headers: {
                        name: 'Назив',
                        sign: 'Знак',
                        created: 'Креиран на'
                    },
                    form: {
                        form_title_new: 'Нова валута',
                        form_title_edit: 'Измена на валута',
                        fields: {
                            name: 'Назив',
                            sign: 'Знак',
                        }
                    },
                },
            },
            contracts: {
                section_title: 'Договори',
                btn_new_title: 'Нов договор',
                headers: {
                    owner: 'Припаѓа на',
                    paid: 'Платен',
                    valid: 'Валиден',
                    duration: 'Времетраење',
                    price: 'Цена',
                    created: 'Креиран на',
                    starts_at: 'Почнува на',
                    expires_at: 'Истекува на',
                    reminders: 'Потсетници'
                },
                form: {
                    form_title_new: 'Нов договор',
                    form_title_edit: 'Измена на договор',
                    fields: {
                        owner: 'Избери бизнис',
                        length: 'Времетраење',
                        price: 'Цена',
                        starts_at: 'Почнува на',
                        expires_at: 'Истекува на',
                        paid: 'Платен',
                        valid: 'Валиден',
                    }
                },
                filters: {
                    lbl_paid: 'Платени договори',
                    lbl_valid: 'Валидни договори',
                    lbl_expired: 'Истечени договори',
                    paid: {
                        all: 'Сите',
                        paid: 'Платени',
                        unpaid: 'Неплатени'
                    },
                    valid: {
                        all: 'Сите',
                        valid: 'Валидни',
                        invalid: 'Невалидни'
                    },
                    expired: {
                        all: 'Сите',
                        valid: 'Тековни',
                        expired: 'Истечени'
                    }
                }
            },
            places: {
                section_title: 'Бизниси',
                btn_new_title: 'Нов бизнис',
                headers: {
                    name: 'Име на бизнис',
                    email: 'e-Пошта',
                    type: 'Тип на бизнис',
                    created: 'Креиран на'
                },
                form: {
                    form_title_new: 'Нов бизнис',
                    form_title_edit: 'Измена на бизнис',
                    fields: {
                        location: 'Изберете локација',
                        avatar: 'Аватар',
                        name: 'Име на бизнисот',
                        email: 'е-Пошта',
                        password: 'Лозинка',
                        phone: 'Телефон',
                        website: 'Веб страна',
                        valute: 'Валута',
                        city: 'Град',
                        category: 'Категорија',
                        subcategory: 'Подкатегорија',
                        description: 'Опис',
                        description_placeholder: 'Опишете го вашиот бизнис',
                        address: 'Адреса',
                        longitude: 'Географска должина',
                        latitude: 'Географска ширина',
                    }
                },
                msg: {
                    place_contains_info: 'Оваа локација содржи информации за вашиот бизнис. Дали сакате да ги употребите?'
                }
            },
            people: {
                section_title: 'Корисници',
                btn_new_title: 'Нов корисник',
                headers: {
                    name: 'Име',
                    email: 'e-Пошта',
                    type: 'Тип на корисник',
                    created: 'Креиран на'
                },
                form: {
                    form_title_new: 'Нов корисник',
                    form_title_edit: 'Измена на корисник',
                    fields: {
                        avatar: 'Аватар',
                        name: 'Име',
                        email: 'е-Пошта',
                        country: 'Држава',
                        city: 'Град',
                        password: 'Лозинка',
                        phone: 'Телефон',
                        gender: 'Пол',
                        description: 'Опис',
                        description_placeholder: 'Неколку зборови за вас',
                        address: 'Адреса',
                    }
                },
                msg: {
                    place_contains_info: 'Оваа локација содржи информации за вашиот бизнис. Дали сакате да ги употребите?'
                }
            },
            offers: {
                section_title: 'Понуди',
                btn_new_title: 'Нова понуда',
                headers: {
                    title: 'Наслов',
                    offered_price: 'Цена',
                    owner: 'Понудена од',
                    created: 'Креирана на'
                },
                form: {
                    form_title_new: 'Нова понуда',
                    form_title_edit: 'Измена на понуда',
                    fields: {
                        owner: 'Изберете бизнис',
                        title: 'Наслов',
                        short_description: 'Краток опис',
                        short_description_placeholder: 'Опишете ја вашата понуда во една реченица',
                        long_description: 'Долг опис',
                        long_description_placeholder: 'Детално опишете ја вашата понуда',
                        promo_image: 'Промотивна слика',
                        real_price: 'Цена',
                        offered_price: 'Понудена цена',
                        global_offer: 'Глобална понуда',
                        global_offer_hint: 'Дали сакате вашата понуда да биде прикажана јавно за туристи и дали може да се користи за креирање глобални понуди во системот?',
                        featured: 'Промотивна понуда',
                        featured_hint: 'Дали сакате вашата понуда да биде прикажана како промоција во преден план за туристите?',
                        notes: 'Забелешки за вашата понуда',
                        notes_placeholder: 'Неколку забелешки за вашата понуда (доколку има)',
                    }
                },
            },
            tickets: {
                section_title: 'Билети',
                headers: {
                    tourist: 'Корисник',
                    place: 'Бизнис',
                    amount: 'Број на билети',
                    created: 'Купена на',
                    used: 'Искористена',
                },
                msg: {
                    use_ticket_title: 'Дали сакате да го искористите овој билет?',
                    use_ticket_msg: 'Доколку го искористите билетот,',
                    used_ticket_visit_txt: 'ќе има искористен билет за'
                },
            },
            tokens: {},
            place: {
                sections: {
                    scan: {
                        section_title: 'Скенирање билети',
                        ticket_valid: 'Билетот е валиден',
                        ticket_invalid: 'Билетот е невалиден',
                        ticket_progress: 'Валидацијата на билетот е во тек',
                        ticket_used: 'Билетот е веќе искористен',
                        ticket_valid_for_usage: 'Билетот е валиден и може да се искористи',
                        ticket_another_location: 'Билетот е за друга локација: {0}',
                        using_ticket: 'Билетот е во обработка...',
                        validating_ticket: 'Валидирање на билетот...'
                    },
                    profile: {
                        form: {
                            fields: {
                                location: 'Побарајте ја вашата локација',
                                avatar: 'Аватар',
                                name: 'Име на бизнисот',
                                email: 'е-Пошта',
                                password: 'Лозинка',
                                phone: 'Телефон',
                                website: 'Веб страна',
                                valute: 'Валута',
                                city: 'Град',
                                category: 'Категорија',
                                subcategory: 'Подкатегорија',
                                description: 'Опис',
                                description_placeholder: 'Опишете го вашиот бизнис',
                                address: 'Адреса',
                                longitude: 'Географска должина',
                                latitude: 'Географска ширина',
                            }
                        },
                    },
                    offers: {
                        section_title: 'Вашите понуди',
                        form: {
                            form_title_new: 'Нова понуда',
                            form_title_edit: 'Измена на понуда',
                            fields: {
                                owner: 'Изберете бизнис',
                                title: 'Наслов',
                                short_description: 'Краток опис',
                                short_description_placeholder: 'Опишете ја вашата понуда во една реченица',
                                long_description: 'Долг опис',
                                long_description_placeholder: 'Детално опишете ја вашата понуда',
                                promo_image: 'Промотивна слика',
                                real_price: 'Цена',
                                offered_price: 'Понудена цена',
                                global_offer: 'Глобална понуда',
                                global_offer_hint: 'Дали сакате вашата понуда да биде прикажана јавно за туристи и дали може да се користи за креирање глобални понуди во системот?',
                                featured: 'Промотивна понуда',
                                featured_hint: 'Дали сакате вашата понуда да биде прикажана како промоција во преден план за туристите?',
                                notes: 'Забелешки за вашата понуда',
                                notes_placeholder: 'Неколку забелешки за вашата понуда (доколку има)',
                                starts_at: 'Започнува на',
                                ends_at: 'Завршува на (не подолго од три месеци)'
                            }
                        },
                        expires_at: 'Завршува на'
                    }
                },
                msg: {
                    place_contains_info: 'Оваа локација содржи информации за вашиот бизнис. Дали сакате да ги употребите?'
                }
            },
            tourist: {
                sections: {}
            },
        },
        other: {
            install_banner: {
                text: 'Дали сакате да ја инсталирате Skopje City Pass како апликација на вашиот уред?',
                btn_dismiss: 'Откажи',
                btn_install: 'Инсталирај'
            }
        },
        global: {
            btn_save: 'Зачувај',
            btn_close: 'Затвори',
            btn_cancel: 'Откажи',
            btn_yes: 'Да',
            btn_no: 'Не',
            btn_ok: 'OK',
            lbl_search: 'Пребарување',
            lbl_reset: 'Ресетирај',
            lbl_filters: 'Филтри',
            msg: {
                delete_ask: 'Дали сте сигурни дека сакате да го избришете записот',
                delete_ask_msg: ' повеќе нема да постои во системот'
            }
        }
    }
}