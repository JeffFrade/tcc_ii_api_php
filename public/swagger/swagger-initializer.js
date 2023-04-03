window.onload = function() {

    // Build a system
    const ui = SwaggerUIBundle({
      url: url = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/swagger.json",
      dom_id: '#swagger-ui',
      deepLinking: true,
      presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIStandalonePreset
      ],
      plugins: [
        SwaggerUIBundle.plugins.DownloadUrl
      ],
      layout: "StandaloneLayout"
    })
    window.ui = ui
}
