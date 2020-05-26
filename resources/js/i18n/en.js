export default {
    message: {
        homepage: {
            welcome_text: 'Welcome to',
            about_us_title: 'About us',
            about_us_text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae porttitor ex. Aliquam erat volutpat. Morbi vitae tristique purus. Nunc dapibus sem at feugiat finibus. Etiam euismod sapien diam. Nullam tincidunt maximus quam porta hendrerit. Integer vel tempus nulla. Aenean sed sem facilisis, dignissim sem at, elementum neque. Mauris eu justo at sapien efficitur tempus.',
            features_title: 'SCP Features',
            blog_title: 'Blog',
            contact_us_title: 'Contact us',
        },
        menus: {
            admin: {
                menu: {
                    items: {
                        home: 'Dashboard',
                        settings: 'Settings',
                        lookups: 'Lookup data',
                        lookup_items: {
                            contract_lengths: 'Contact lengths',
                            languages: 'Languages',
                            user_types: 'User types',
                            categories: 'Categories',
                            countries: 'Countries',
                            cities: 'Cities',
                            genders: 'Genders',
                            valutes: 'Currencies',
                        },
                        contracts: 'Contracts',
                        places: 'Places',
                        people: 'Tourists',
                        offers: 'Offers',
                        tickets: 'Tickets',
                        tokens: 'Tokens',
                        about: 'About SCP',
                        logout: 'Logout',
                    }
                }
            },
            place: {
                menu: {
                    items: {
                        home: 'Dashboard',
                        scan: 'Scan',
                        profile: 'Profile',
                        offers: 'Offers',
                        profile_preview: 'Profile preview',
                        about: 'About SCP',
                        logout: 'Logout',
                    }
                }
            },
            tourist: {}
        },
        widgets: {
            todaytickets: {
                today_text: 'Tickets bought today',
                week_text: 'This week',
                month_text: 'This month'
            },
            dailyvisitors: {
                today_text: 'Today\'s visitors',
                week_text: 'This week',
                month_text: 'This month'
            },
            dailynewusers: {
                today_text: 'Today\'s new tourists',
                week_text: 'This week',
                month_text: 'This month'
            },
            todayplaces: {
                today_text: 'Today\'s visited places',
                week_text: 'This week',
                month_text: 'This month'
            },
            mostvisitedplaces: {
                title: 'Most visited places',
                header_place: 'Place',
                header_visits: 'Visits',
                footer: 'Most visited places of all time'
            },
            monthlytickets: {
                title: 'This month\'s tickets',
                total_tickets_lbl: 'Total tickets',
                used_tickets_lbl: 'Used tickets',
                footer: 'Total amount of sold & used tickets this month'
            },
            yearlyvisitors: {
                title: 'This year\'s visitors',
                total_visitors_lbl: 'Total visitors',
                footer: 'Total amount of visitors this year'
            },
            yearlytickets: {
                title: 'This year\'s tickets',
                total_tickets_lbl: 'Total tickets',
                used_tickets_lbl: 'Used tickets',
                footer: 'Total amount of sold & used tickets for this year'
            },
        },
        sections: {
            settings: {
                settings_title: 'Settings',
                settings_subtitle: 'Global settings for your application',
                labels: {
                    site_name: 'Application name',
                    site_logo: 'Logo',
                    frontpage_template: 'Front page template',
                    contact_phone: 'Telephone',
                    contact_email: 'Email',
                    contact_address: 'Address',
                    default_language: 'Default language',
                    last_updated: 'Last changed',
                    contract_check: 'Contract check',
                    contract_check_hint: 'Should business users contracts be checked for validity?',
                }
            },
            lookups: {
                contract_lengths: {
                    section_title: 'Contract lengths',
                    btn_new_title: 'New contract length',
                    headers: {
                        name: 'Name',
                        duration: 'Duration (months)',
                        price: 'Price'
                    },
                    form: {
                        form_title_new: 'New contract length',
                        form_title_edit: 'Edit contract length',
                        fields: {
                            name: 'Name',
                            duration: 'Duration (months)',
                            price: 'Price',
                            valute: 'Currency'
                        }
                    },
                    durations: {
                        months_1: 'One month',
                        months_3: 'Three months',
                        months_6: 'Six months',
                        months_12: 'One year',
                        months_24: 'Two years',
                        months_36: 'Three years',
                    }
                },
                languages: {
                    section_title: 'Languages',
                    btn_new_title: 'New language',
                    headers: {
                        name: 'Name',
                        locale: 'Locale',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New language',
                        form_title_edit: 'Edit language',
                        fields: {
                            name: 'Name',
                            locale: 'Locale'
                        }
                    },
                },
                user_types: {
                    section_title: 'User types',
                    btn_new_title: 'New user type',
                    headers: {
                        name: 'Name',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New user type',
                        form_title_edit: 'Edit user type',
                        fields: {
                            name: 'Name'
                        }
                    },
                },
                categories: {
                    section_title: 'Categories',
                    btn_new_title: 'New category',
                    headers: {
                        name: 'Name',
                        parent: 'Parent category',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New category',
                        form_title_edit: 'Edit category',
                        fields: {
                            name: 'Name',
                            parent: 'Parent category',
                        }
                    },
                },
                countries: {
                    section_title: 'Countries',
                    btn_new_title: 'New country',
                    headers: {
                        name: 'Name',
                        code: 'Code',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New country',
                        form_title_edit: 'Edit country',
                        fields: {
                            name: 'Name',
                            code: 'Code',
                        }
                    },
                },
                cities: {
                    section_title: 'Cities',
                    btn_new_title: 'New city',
                    headers: {
                        name: 'Name',
                        country: 'Country',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New city',
                        form_title_edit: 'Edit city',
                        fields: {
                            name: 'Name',
                            country: 'Country',
                        }
                    },
                },
                genders: {
                    section_title: 'Genders',
                    btn_new_title: 'New gender',
                    headers: {
                        name: 'Name',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New gender',
                        form_title_edit: 'Edit gender',
                        fields: {
                            name: 'Name',
                        }
                    },
                },
                valutes: {
                    section_title: 'Currencies',
                    btn_new_title: 'New currency',
                    headers: {
                        name: 'Name',
                        sign: 'Sign',
                        created: 'Created on'
                    },
                    form: {
                        form_title_new: 'New currency',
                        form_title_edit: 'Edit currency',
                        fields: {
                            name: 'Name',
                            sign: 'Sign',
                        }
                    },
                },
            },
            contracts: {
                section_title: 'Contracts',
                btn_new_title: 'New contract',
                headers: {
                    owner: 'Owner',
                    paid: 'Paid',
                    valid: 'Valid',
                    duration: 'Duration',
                    price: 'Price',
                    created: 'Created on',
                    starts_at: 'Starts at',
                    expires_at: 'Expires at',
                    reminders: 'Reminders'
                },
                form: {
                    form_title_new: 'New contract',
                    form_title_edit: 'Edit contract',
                    fields: {
                        owner: 'Select a place',
                        duration: 'Duration',
                        price: 'Price',
                        starts_at: 'Starts at',
                        expires_at: 'Expires at',
                        paid: 'Paid',
                        valid: 'Valid',
                    }
                },
                filters: {
                    lbl_paid: 'Paid contracts',
                    lbl_valid: 'Valid contracts',
                    lbl_expired: 'Expired contracts',
                    paid: {
                        all: 'All',
                        paid: 'Paid',
                        unpaid: 'Unpaid'
                    },
                    valid: {
                        all: 'All',
                        valid: 'Valid',
                        invalid: 'Invalid'
                    },
                    expired: {
                        all: 'All',
                        valid: 'Ongoing',
                        expired: 'Expired'
                    }
                }
            },
            places: {
                section_title: 'Places',
                btn_new_title: 'New place',
                headers: {
                    name: 'Name',
                    email: 'Email',
                    type: 'Type',
                    created: 'Created on'
                },
                form: {
                    form_title_new: 'New place',
                    form_title_edit: 'Edit place',
                    fields: {
                        location: 'Choose location...',
                        avatar: 'Avatar',
                        name: 'Name',
                        email: 'Email',
                        password: 'Password',
                        phone: 'Phone',
                        website: 'Website',
                        valute: 'Currency',
                        city: 'City',
                        category: 'Category',
                        subcategory: 'Subcategory',
                        description: 'Description',
                        description_placeholder: 'Describe your business',
                        address: 'Address',
                        longitude: 'Longitude',
                        latitude: 'Latitude',
                    }
                },
                msg: {
                    place_contains_info: 'This location contains information about your place. Do you want to use it?'
                }
            },
            people: {
                section_title: 'People',
                btn_new_title: 'New user',
                headers: {
                    name: 'Name',
                    email: 'Email',
                    type: 'Type',
                    created: 'Created on'
                },
                form: {
                    form_title_new: 'New user',
                    form_title_edit: 'Edit user',
                    fields: {
                        avatar: 'Avatar',
                        name: 'Name',
                        email: 'Email',
                        country: 'Country',
                        city: 'City',
                        password: 'Password',
                        phone: 'Phone',
                        gender: 'Gender',
                        description: 'Description',
                        description_placeholder: 'Write us a few words about yourself...',
                        address: 'Address',
                    }
                },
            },
            offers: {
                section_title: 'Offers',
                btn_new_title: 'New offer',
                headers: {
                    title: 'Title',
                    offered_price: 'Price',
                    owner: 'Offered from',
                    created: 'Created on'
                },
                form: {
                    form_title_new: 'New offer',
                    form_title_edit: 'Edit offer',
                    fields: {
                        owner: 'Select a place...',
                        title: 'Title',
                        short_description: 'Short description',
                        short_description_placeholder: 'Describe your offer in one sentence',
                        long_description: 'Long description',
                        long_description_placeholder: 'Describe your offer in details',
                        promo_image: 'Promo image',
                        real_price: 'Real price',
                        offered_price: 'Offered price',
                        global_offer: 'Global offer',
                        global_offer_hint: 'Would you like this offer to be publicly available for tourists and be used by our system for bundled offers (multiple tickets for one offer)?',
                        featured: 'Featured offer',
                        featured_hint: 'Would you like this offer to be displayed in our top section for the tourists?',
                        notes: 'Notes',
                        notes_placeholder: 'If any, please state some notes about this offer',
                    }
                },
            },
            tickets: {
                section_title: 'Tickets',
                headers: {
                    tourist: 'Tourist',
                    place: 'Place',
                    amount: 'Amount',
                    created: 'Bought on',
                    used: 'Used',
                },
                msg: {
                    use_ticket_title: 'Do you really want to use this ticket?',
                    use_ticket_msg: 'If you use this ticket,',
                    used_ticket_visit_txt: 'will have used ticked for'
                },
            },
            tokens: {},
            place: {
                sections: {
                    scan: {
                        section_title: 'Tickets scanner',
                        ticket_valid: 'Ticket is valid',
                        ticket_invalid: 'Ticket is NOT valid',
                        ticket_progress: 'Ticket validation in progress...',
                        ticket_used: 'Ticket has already been used',
                        ticket_valid_for_usage: 'Ticket is valid and ready to be used',
                        ticket_another_location: 'This ticket is for a different location: {0}',
                        using_ticket: 'Processing ticket...',
                        validating_ticket: 'Ticket validation...'
                    },
                    profile: {
                        form: {
                            fields: {
                                location: 'Choose location...',
                                avatar: 'Avatar',
                                name: 'Name',
                                email: 'Email',
                                password: 'Password',
                                phone: 'Phone',
                                website: 'Website',
                                valute: 'Currency',
                                city: 'City',
                                category: 'Category',
                                subcategory: 'Subcategory',
                                description: 'Description',
                                description_placeholder: 'Describe your business',
                                address: 'Address',
                                longitude: 'Longitude',
                                latitude: 'Latitude',
                            }
                        },
                    },
                    offers: {
                        section_title: 'Offers',
                        form: {
                            form_title_new: 'New offer',
                            form_title_edit: 'Edit offer',
                            fields: {
                                owner: 'Select a place...',
                                title: 'Title',
                                short_description: 'Short description',
                                short_description_placeholder: 'Describe your offer in one sentence',
                                long_description: 'Long description',
                                long_description_placeholder: 'Describe your offer in details',
                                promo_image: 'Promo image',
                                real_price: 'Real price',
                                offered_price: 'Offered price',
                                global_offer: 'Global offer',
                                global_offer_hint: 'Would you like this offer to be publicly available for tourists and be used by our system for bundled offers (multiple tickets for one offer)?',
                                featured: 'Featured offer',
                                featured_hint: 'Would you like this offer to be displayed in our top section for the tourists?',
                                notes: 'Notes',
                                notes_placeholder: 'If any, please state some notes about this offer',
                                starts_at: 'Starts at',
                                ends_at: 'Ends at (not longer than three months)'
                            }
                        },
                        expires_at: 'Expires at'
                    }
                },
                msg: {
                    place_contains_info: 'This location contains information about your place. Do you want to use it?'
                }
            },
            tourist: {},
        },
        other: {
            install_banner: {
                text: 'Do you want to install Skopje City Pass as an application on your device?',
                btn_dismiss: 'Dismiss',
                btn_install: 'Install'
            }
        },
        global: {
            btn_save: 'Save',
            btn_close: 'Close',
            btn_cancel: 'Cancel',
            btn_yes: 'Yes',
            btn_no: 'No',
            btn_ok: 'OK',
            lbl_search: 'Search',
            lbl_reset: 'Reset',
            lbl_filters: 'Filters',
            msg: {
                delete_ask: 'Are you sure you want to delete',
                delete_ask_msg: ' will not exist in our system anymore'
            }
        }
    }
}