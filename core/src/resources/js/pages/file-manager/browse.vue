<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_file_manager')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("fileManager.title") }}</h3>
          </div>
          <iframe v-if="isShow" :src="urlIframe" class="file-manager__iframe" />
          <div v-else>
            {{ message }}
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("fileManager.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
export default {
  name: "FileManagerBrowse",
  components: {},
  data() {
    return {
      statusCode: null,
      message: null,
      isShow: true,
      urlIframe: null,
    };
  },
  async created() {
    await this.requestCheckPageIFrame();
  },
  methods: {
    async requestCheckPageIFrame() {
      this.$openLoader();

      this.$resource
        .get(this.urlFileManager)
        .then((result) => {
          this.urlIframe = this.urlFileManager;
          console.log(this.urlIframe,'u');
          this.isShow = true;
        })
        .catch((error) => {
          const { message } = error;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: message,
            color: "danger",
          });
          this.isShow = false;
        })
        .finally(() => {
          this.$closeLoader();
        });
    },
  },
  computed: {
    urlFileManager() {
      const host = window.location.origin;
      const token = localStorage.getItem("token");
      const url = `${host}/filemanager?type=Files&token=${token}`;
      return url;
    },
  },
};
</script>
