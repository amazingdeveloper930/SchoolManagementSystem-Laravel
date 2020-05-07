( función ( $ ) {

    function  appendContent ( $ el , content ) {
        si ( ! contenido) volver ;

        // Prueba simple para un elemento jQuery
        $ el . añadir ( contenido . jquery  ?  contenido . clone () : contenido);
    }

    function  appendBody ( $ body , $ element , opt ) {
        // Clon para seguridad y conveniencia.
        // Llama a clone (withDataAndEvents = true) para copiar los valores del formulario.
        var $ content =  $ element . clon ( opt . formValues );

        if ( opt . formValues ) {
            // Copie los valores de selección original y área de texto en su contraparte clonada
            // Se recupera la incapacidad de clonar los valores de selección y de área de texto con clonar (verdadero)
            copyValues ($ element, $ content, ' select, textarea ' );
        }

        if ( opt . removeScripts ) {
            $ contenido . encontrar ( ' script ' ). eliminar ();
        }

        if ( opt . printContainer ) {
            // toma $ .selector como contenedor
            $ contenido . appendTo ($ body);
        } else {
            // de lo contrario solo imprimir elementos interiores del contenedor
            $ contenido . cada ( función () {
                $ ( esto ). niños (). appendTo ($ body)
            });
        }
    }

    // Copia los valores de origen a clon para que se pasen en elementSelector
    function  copyValues ( origin , clone , elementSelector ) {
        var $ originalElements =  origen . find (elementSelector);

        clon . buscar (elementSelector). cada ( función ( índice , elemento ) {
            $ (artículo). val ( $ originalElements . eq (index). val ());
        });
    }

    var opt;
    $ . fn . printThis  =  función ( opciones ) {
        opt =  $ . extender ({}, $ . fn . printThis . por defecto , opciones);
        var $ element =  this  instanceof jQuery ?  esto  :  $ ( esto );

        var strFrameName =  " printThis- "  + ( new  Date ()). getTime ();

        if ( ventana . ubicación . nombre de host  ! ==  documento . dominio  &&  navegador . userAgent . match ( / msie / i )) {
            // Los hacks feos de IE debido a que IE no hereda document.domain de los padres
            // comprueba si document.domain se establece comparando el nombre de host con document.domain
            var iframeSrc =  " javascript: document.write ( \" <head> <script> document.domain = \\\ " "  +  document . domain  +  " \\\" ; </ s "  +  " cript> </head> <body> </body> \ " ) " ;
            var printI =  documento . createElement ( ' iframe ' );
            printI . name  =  " printIframe " ;
            printI . id  = strFrameName;
            printI . className  =  " MSIE " ;
            documento . cuerpo . appendChild (printI);
            printI . src  = iframeSrc;

        } else {
            // otros navegadores heredan document.domain, e IE funciona si document.domain no está configurado explícitamente
            var $ frame =  $ ( " <iframe id = ' "  + strFrameName +  " ' name = 'printIframe' /> " );
            $ marco . appendTo ( " cuerpo " );
        }

        var $ iframe =  $ ( " # "  + strFrameName);

        // muestra el marco si está en modo de depuración
        if ( ! opt . debug ) $ iframe . css ({
            posición :  " absoluta " ,
            ancho :  " 0px " ,
            altura :  " 0px " ,
            izquierda :  " -600px " ,
            arriba :  " -600px "
        });

        // antes de imprimir callback
        if ( typeof  opt . beforePrint  ===  " function " ) {
            opt . beforePrint ();
        }

        // $ iframe.ready () y $ iframe.load fueron inconsistentes entre los navegadores
        setTimeout ( function () {

            // Agrega doctype para arreglar la diferencia de estilo entre la impresión y el renderizado
            function  setDocType ( $ iframe , doctype ) {
                var win, doc;
                win =  $ iframe . obtener ( 0 );
                ganar =  ganar . contentWindow  ||  victoria . contentDocument  || ganar;
                doc =  ganar . documento  ||  victoria . contentDocument  || ganar;
                doc . abrir ();
                doc . escribir (doctype);
                doc . cerrar ();
            }

            if ( opt . doctypeString ) {
                setDocType ($ iframe, opt . doctypeString );
            }

            var $ doc =  $ iframe . contenidos (),
                $ head =  $ doc . encontrar ( " cabeza " ),
                $ body =  $ doc . encontrar ( " cuerpo " ),
                $ base =  $ ( ' base ' ),
                baseURL;

            // agregar una etiqueta base para asegurar que los elementos usen el dominio principal
            if ( opt . base  ===  true  &&  $ base . length  >  0 ) {
                // toma la etiqueta base de la página original
                baseURL =  $ base . attr ( ' href ' );
            } else  if si ( typeof  opt . base  ===  ' string ' ) {
                // Se proporciona una cadena base exacta
                baseURL =  opt . de base ;
            } else {
                // Usa la URL de la página como base
                baseURL =  documento . ubicación . protocolo  +  ' // '  +  documento . ubicación . albergar ;
            }

            $ cabeza . append ( ' <base href = " '  + baseURL +  ' "> ' );

            // importar hojas de estilo de la página
            if ( opt . importCSS ) $ ( " link [rel = stylesheet] " ). cada ( función () {
                var href =  $ ( esto ). attr ( " href " );
                if (href) {
                    var media =  $ ( esto ). attr ( " media " ) ||  " todos " ;
                    $ cabeza . append ( " <link type = 'text / css' rel = 'stylesheet' href = ' "  + href +  " ' media = ' "  + media +  " '> " );
                }
            });

            // importar etiquetas de estilo
            if ( opt . importStyle ) $ ( " estilo " ). cada ( función () {
                $ cabeza . anexar ( este . outerHTML );
            });

            // añadir título de la página
            if ( opt . pageTitle ) $ head . append ( " <title> "  +  opt . pageTitle  +  " </title> " );

            // importar hojas de estilo adicionales
            if ( opt . loadCSS ) {
                if ( $ . isArray ( opt . loadCSS )) {
                    jQuery . cada ( opt . loadCSS , función ( índice , valor ) {
                        $ cabeza . append ( " <link type = 'text / css' rel = 'stylesheet' href = ' "  +  this  +  " '> " );
                    });
                } else {
                    $ cabeza . append ( " <link type = 'text / css' rel = 'stylesheet' href = ' "  +  opt . loadCSS  +  " '> " );
                }
            }

            var pageHtml =  $ ( ' html ' ) [ 0 ];

            // CSS VAR en la etiqueta html cuando se aplica dinámicamente, por ejemplo, document.documentElement.style.setProperty ("- foo", barra);
            $ doc . encontrar ( ' html ' ). prop ( ' estilo ' , pageHtml . estilo . cssText );

            // copiar las clases de etiquetas 'raíz'
            var etiqueta =  opt . copyTagClasses ;
            si (etiqueta) {
                tag = tag ===  true  ?  ' bh '  : etiqueta;
                if ( tag . indexOf ( ' b ' ) ! ==  - 1 ) {
                    $ cuerpo . addClass ( $ ( ' body ' ) [ 0 ]. className );
                }
                if ( tag . indexOf ( ' h ' ) ! ==  - 1 ) {
                    $ doc . encontrar ( ' html ' ). addClass ( pageHtml . className );
                }
            }

            // encabezado de impresión
            appendContent ($ body, opt . header );

            if ( opt . canvas ) {
                // agregar ID de datos de lienzo para facilitar el acceso después de la clonación.
                var canvasId =  0 ;
                // .addBack ('lienzo') agrega el elemento de nivel superior si es un lienzo.
                $ elemento . encontrar ( ' lienzo ' ). addBack ( ' lienzo ' ). cada ( función () {
                    $ ( esto ). attr ( ' data-printthis ' , canvasId ++ );
                });
            }

            appendBody ($ body, $ element, opt);

            if ( opt . canvas ) {
                // Re-dibujar nuevos lienzos haciendo referencia a los originales
                $ cuerpo . encontrar ( ' lienzo ' ). cada ( función () {
                    var cid =  $ ( esto ). datos ( ' printthis ' ),
                        $ src =  $ ( ' [data-printthis = " '  + cid +  ' "] ' );

                    esta . getContext ( ' 2d ' ). drawImage ($ src [ 0 ], 0 , 0 );

                    // Remover el marcado del original.
                    if ( $ . isFunction ( $ . fn . removeAttr )) {
                        $ src . removeAttr ( ' data-printthis ' );
                    } else {
                        $ . cada ($ src, función ( i , el ) {
                            EL . removeAttribute ( ' data-printthis ' )
                        });
                    }
                });
            }

            // eliminar estilos en línea
            if ( opt . removeInline ) {
                // Asegúrese de que haya un selector, incluso si se ha eliminado por error
                selector de var =  opt . removeInlineSelector  ||  ' * ' ;
                // $ .removeAttr disponible jQuery 1.7+
                if ( $ . isFunction ( $ . removeAttr )) {
                    $ cuerpo . encontrar (selector). removeAttr ( " estilo " );
                } else {
                    $ cuerpo . encontrar (selector). attr ( " estilo " , " " );
                }
            }

            // imprimir "pie de página"
            appendContent ($ body, opt . footer );

            // adjuntar la función del controlador de eventos al evento antes de imprimir
            function  attachOnBeforePrintEvent ( $ iframe , beforePrintHandler ) {
                var win =  $ iframe . obtener ( 0 );
                ganar =  ganar . contentWindow  ||  victoria . contentDocument  || ganar;

                if ( typeof beforePrintHandler ===  " function " ) {
                    if ( ' matchMedia '  en win) {
                        victoria . matchMedia ( ' imprimir ' ). addListener ( function ( mql ) {
                            if ( mql . match )   beforePrintHandler ();
                        });
                    } else {
                        victoria . onbeforeprint  = beforePrintHandler;
                    }
                }
            }
            attachOnBeforePrintEvent ($ iframe, opt . beforePrint );

            setTimeout ( function () {
                if ( $ iframe . hasClass ( " MSIE " )) {
                    // comprueba si el iframe fue creado con el hackeo feo
                    // y realizar otro feo truco por necesidad.
                    ventana . cuadros [ " printIframe " ]. enfoque ();
                    $ cabeza . append ( " <script> window.print (); </ s "  +  " cript> " );
                } else {
                    // método apropiado
                    if ( document . queryCommandSupported ( " print " )) {
                        $ iframe [ 0 ]. contentWindow . documento . execCommand ( " imprimir " , falso , nulo );
                    } else {
                        $ iframe [ 0 ]. contentWindow . enfoque ();
                        $ iframe [ 0 ]. contentWindow . imprimir ();
                    }
                }

                // eliminar iframe despues de imprimir
                if ( ! opt . debug ) {
                    setTimeout ( function () {
                        $ iframe . eliminar ();

                    }, 1000 );
                }

                // después de la devolución de llamada de impresión
                if ( typeof  opt . afterPrint  ===  " function " ) {
                    opt . afterPrint ();
                }

            }, opt . printDelay );

        }, 333 );

    };

    // valores por defecto
    $ . fn . printThis . por defecto  = {
        debug :  false ,                // muestra el iframe para la depuración
        importCSS :  true ,             // importar página principal css
        importStyle :  false ,          // importar etiquetas de estilo
        printContainer :  true ,        // imprimir contenedor externo / $. selector
        loadCSS :  " " ,                 // ruta al archivo css adicional: use una matriz [] para múltiples
        pageTitle :  " " ,               // agregar título a la página de impresión
        removeInline :  false ,         // elimina los estilos en línea de los elementos impresos
        removeInlineSelector :  " * " ,   // selectores personalizados para filtrar los estilos en línea. removeInline debe ser verdadero
        printDelay :  333 ,             // retraso de impresión variable
        encabezado :  nulo ,                // prefijo a html
        pie de página :  nulo ,                // postfix a html
        base :  false ,                 // conserva la etiqueta BASE o acepta una cadena para la URL
        formValues :  true ,            // preservar los valores de entrada / forma
        lienzo :  falso ,               // copiar lienzo contenido
        doctypeString :  ' <! DOCTYPE html> ' , // ingrese un doctype diferente para el marcado anterior
        removeScripts :  false ,        // elimina las etiquetas de script del contenido de impresión
        copyTagClasses :  false ,       // copia las clases de la etiqueta html & body
        beforePrintEvent :  null ,      // función de devolución de llamada para printEvent en iframe
        beforePrint :  null ,           // función llamada antes de que se complete iframe
        afterPrint :  null             // función llamada antes de eliminar iframe
    };
}) (jQuery);