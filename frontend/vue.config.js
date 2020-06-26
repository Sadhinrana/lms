module.exports = {
  lintOnSave: false,
  publicPath: process.env.NODE_ENV === "production" ? "/" : "/",
  // publicPath: process.env.NODE_ENV === "production" ? "/LMS/LMS-App/lms/frontend/live/" : "/",
  outputDir: "live",
  devServer: {
    disableHostCheck: true
  }
};
//VUE_APP_API_URL=http://localhost:1234/LMS/LMS-App/lms/public
//VUE_APP_API_URL=https://celtenglish.com/public