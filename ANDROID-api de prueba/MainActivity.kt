package com.tuapp.webview  // ← Cambia esto por tu paquete

import android.annotation.SuppressLint
import android.os.Bundle
import android.view.View
import android.webkit.*
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout

class MainActivity : AppCompatActivity() {


    //Solo cambia estas dos líneas

    // IP de tu PC en la red local (abre CMD → ipconfig → IPv4)
    private val SERVER_IP   = "192.168.1.X"   // ← Pon tu IP aquí
    private val SERVER_PORT = "80"             // Puerto XAMPP (80 por defecto)

    // URL de tu página principal en XAMPP
    private val WEB_URL = "http://$SERVER_IP:$SERVER_PORT/tu_proyecto/index.php"
    //                                             ↑ Cambia por tu carpeta en htdocs

    // ═══════════════════════════════════════════════════
    //  VARIABLES DE VISTA
    // ═══════════════════════════════════════════════════
    private lateinit var webView: WebView
    private lateinit var progressBar: ProgressBar
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var tvError: TextView

    // ═══════════════════════════════════════════════════
    //  CICLO DE VIDA
    // ═══════════════════════════════════════════════════
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        bindViews()
        setupWebView()
        setupSwipeRefresh()
        loadWebPage()
    }

    override fun onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack()       // Navegación atrás dentro del WebView
        } else {
            super.onBackPressed()  // Salir de la app
        }
    }

    // ═══════════════════════════════════════════════════
    //  CONFIGURACIÓN DEL WEBVIEW
    // ═══════════════════════════════════════════════════
    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        val settings = webView.settings

        // Habilitar JavaScript (necesario para la mayoría de páginas web)
        settings.javaScriptEnabled = true

        // Soporte para contenido mixto HTTP dentro de HTTPS
        settings.mixedContentMode = WebSettings.MIXED_CONTENT_ALWAYS_ALLOW

        // Optimizaciones de rendimiento
        settings.domStorageEnabled     = true
        settings.databaseEnabled       = true
        settings.cacheMode             = WebSettings.LOAD_DEFAULT
        settings.useWideViewPort       = true
        settings.loadWithOverviewMode  = true
        settings.builtInZoomControls   = true
        settings.displayZoomControls   = false

        // Evitar que links externos abran el navegador del sistema
        webView.webViewClient = object : WebViewClient() {

            override fun shouldOverrideUrlLoading(
                view: WebView?, request: WebResourceRequest?
            ): Boolean {
                val url = request?.url?.toString() ?: return false
                // Solo permite URLs de tu servidor XAMPP
                return if (url.startsWith("http://$SERVER_IP")) {
                    false  // El WebView maneja la URL
                } else {
                    true   // Bloquea URLs externas
                }
            }

            override fun onPageStarted(view: WebView?, url: String?, favicon: android.graphics.Bitmap?) {
                progressBar.visibility = View.VISIBLE
                tvError.visibility     = View.GONE
            }

            override fun onPageFinished(view: WebView?, url: String?) {
                progressBar.visibility = View.GONE
                swipeRefresh.isRefreshing = false
            }

            override fun onReceivedError(
                view: WebView?, request: WebResourceRequest?, error: WebResourceError?
            ) {
                progressBar.visibility = View.GONE
                swipeRefresh.isRefreshing = false
                mostrarError("No se pudo conectar al servidor.\n\nVerifica que:\n• XAMPP está corriendo\n• La IP es correcta ($SERVER_IP)\n• Estás en la misma red WiFi")
            }
        }

        // Consola JavaScript -> Logcat (útil para debug pero esto no lo ve el usuario :D)
        webView.webChromeClient = object : WebChromeClient() {
            override fun onProgressChanged(view: WebView?, newProgress: Int) {
                progressBar.progress = newProgress
            }
            override fun onConsoleMessage(msg: ConsoleMessage?): Boolean {
                android.util.Log.d("WebView-JS", msg?.message() ?: "")
                return true
            }
        }
    }


    //  swipe (o arrastrar el dedo para abajo pa actulizar) es una funcion util para cuadno hagamos cambios

    private fun setupSwipeRefresh() {
        swipeRefresh.setColorSchemeResources(android.R.color.holo_blue_bright)
        swipeRefresh.setOnRefreshListener {
            tvError.visibility = View.GONE
            webView.reload()
        }
    }


    //  CARGA LA PÁGINA

    private fun loadWebPage() {
        tvError.visibility = View.GONE
        webView.loadUrl(WEB_URL)
    }

    private fun mostrarError(mensaje: String) {
        tvError.visibility = View.VISIBLE
        tvError.text       = mensaje
    }

    private fun bindViews() {
        webView      = findViewById(R.id.webView)
        progressBar  = findViewById(R.id.progressBar)
        swipeRefresh = findViewById(R.id.swipeRefresh)
        tvError      = findViewById(R.id.tvError)
    }
}
