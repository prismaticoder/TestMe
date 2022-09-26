<template>

<v-expansion-panels tile accordion>
    <v-expansion-panel class="p-3">
        <v-expansion-panel-header>Change Account Password</v-expansion-panel-header>
        <v-expansion-panel-content>
            <v-card class="mx-auto" max-width="500">
                <v-card-title class="title font-weight-regular justify-space-between">
                    <span>{{ currentTitle }}</span>
                    <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar>
                </v-card-title>
                <v-window v-model="step">
                    <v-window-item :value="1">
                        <v-card-text>
                            <form @submit.prevent="confirmPassword">
                                <v-alert dense type="error" dismissible v-model="showAlert">
                                    Incorrect Password. Try again
                                </v-alert>
                                <v-text-field
                                    label="Password"
                                    :append-icon="showOld ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="showOld ? 'text' : 'password'"
                                    @click:append="showOld = !showOld"
                                    v-model="oldPassword"
                                    placeholder="Current Password"
                                ></v-text-field>

                                <v-btn type="submit" :disabled="!oldPassword" :loading="loading" :color="yellow" tile class="mt-3">
                                    Confirm
                                </v-btn>
                            </form>
                        </v-card-text>
                    </v-window-item>

                    <v-window-item :value="2">
                        <v-card-text>
                            <form @submit.prevent="updatePassword">
                                <v-text-field
                                    label="Password"
                                    :append-icon="showNew ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="showNew ? 'text' : 'password'"
                                    @click:append="showNew = !showNew"
                                    v-model="newPassword"
                                ></v-text-field>
                                <v-text-field
                                    label="Confirm Password"
                                    :disabled="!newPassword"
                                    :append-icon="showConf ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="showConf ? 'text' : 'password'"
                                    @click:append="showConf = !showConf"
                                    v-model="confPassword"
                                ></v-text-field>

                                <v-btn type="submit" :disabled="newPassword !== confPassword || !newPassword || !confPassword" :loading="loading" :color="yellow" tile class="mt-3">
                                    Update Password
                                </v-btn>
                            </form>
                        </v-card-text>
                    </v-window-item>

                    <v-window-item :value="3">
                        <div class="pa-4 text-center">
                            <v-alert dense text type="success">
                                {{message}}
                            </v-alert>
                        </div>
                    </v-window-item>
                </v-window>
            </v-card>
        </v-expansion-panel-content>
    </v-expansion-panel>
</v-expansion-panels>

</template>

<script>
export default {
    name: "ManageAccount",
    data() {
        return {
            oldPassword: null,
            newPassword: null,
            confPassword: null,
            showAlert: false,
            step: 1,
            message: null,
            showOld: false,
            showNew: false,
            showConf: false,
            loading: false,
            yellow:  "#e67d23",
            rule: [
                (val) => {
                    return val == this.newPassword || "Error: Passwords must be equal"
                }
            ]
        }
    },
    computed: {
      currentTitle () {
        switch (this.step) {
          case 1: return 'Enter Current Password'
          case 2: return 'Create New Password'
          default: return 'Password updated!'
        }
      },
    },
    methods: {
        confirmPassword() {
            this.loading = true

            this.$http.post('update-password/verify', {
                password: this.oldPassword
            })
            .then(res => {
                this.loading = false
                if (res.data.password_is_valid) {
                    this.step++
                }
                else {
                    this.showAlert = true
                }
            })
            .catch(err => {
                this.loading = false
                console.log(err.response.data)
                alert("Error")
            })
        },
        updatePassword() {
            this.loading = true

            this.$http.post('update-password', {
                old_password: this.oldPassword,
                new_password: this.newPassword
            })
            .then(res => {
                this.loading = false
                this.message = res.data.message
                this.step++
            })
            .catch(err => {
                this.loading = false
                console.log(err.response.data)
                alert("Sorry, there was an error updating your password, please refresh the page and try again.");
            })
        }
    }
}
</script>
