<template>
    <v-dialog v-model="dialog" persistent max-width="500" :fullscreen="$vuetify.breakpoint.mdAndDown">
        <v-card>
            <v-card-title>
                <span class="headline">Sign in</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field name="email" v-model="email" type="email" label="Email" required :error-messages="loginErrors.email"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field name="password" v-model="password" type="password" label="Password" :error-messages="loginErrors.password"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="$emit('close')">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="success darken-1" text @click="doLogin">Sign in</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "LoginForm",
        props: ['showLogin', 'logerrors'],
        watch: {
            showLogin(newVal, oldVal) {
                this.dialog = newVal
                return newVal
            },
            logerrors(newVal, oldVal) {
                this.loginErrors = newVal
                return newVal
            },
        },
        data: () => ({
            dialog: false,
            email: '',
            password: '',
            loginErrors: []
        }),
        methods: {
            doLogin() {
                this.$emit('login', {
                    email: this.email,
                    password: this.password
                })
            }
        }
    }
</script>

<style scoped>

</style>