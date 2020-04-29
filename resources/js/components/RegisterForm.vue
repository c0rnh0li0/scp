<template>
    <v-dialog v-model="dialog" persistent max-width="500" :fullscreen="$vuetify.breakpoint.mdAndDown">
        <v-card>
            <v-card-title>
                <span class="headline">Create account</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field name="name" v-model="name" label="Name" required :error-messages="registerErrors.name"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field name="email" v-model="email" type="email" label="Email" required :error-messages="registerErrors.email"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field name="password" v-model="password" type="password" label="Password" required :error-messages="registerErrors.password"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field name="c_password" v-model="password_confirmation" type="password" label="Confirm Password" required :error-messages="registerErrors.c_password"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-checkbox v-model="is_company" label="I am a company"></v-checkbox>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" text @click="$emit('close')">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="success darken-1" text @click="register">Sign up</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "RegisterForm",
        props: ['showRegister', 'regerrors'],
        watch: {
            showRegister(newVal, oldVal) {
                this.dialog = newVal
                return newVal
            },
            regerrors(newVal, oldVal) {
                this.registerErrors = newVal
                return newVal
            },
        },
        data: () => ({
            dialog: false,
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            is_company: false,
            registerErrors: []
        }),
        methods: {
            register() {
                this.$emit('register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    c_password: this.password_confirmation,
                    is_company: this.is_company.toString()
                })
            },
        }
    }
</script>

<style scoped>

</style>