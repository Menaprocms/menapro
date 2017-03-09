var JSBlocks = {
    currentBlock: null,
    row: null,
    col: null,
    subrow: null,
    lang: default_lang,
    errors: [],
    categories: {
        text: [],
        image: [],
        video: [],
        social: [],
        other: [],
        charts: [],
        news:[],
        thirds: []
    },
    blocks: {},

    debug: false,

    /*---------------------------*/
    /**
     * Función que se llama desde cada bloque cuando se inicia banana para imágenes.
     * @param multiple true si permitimos la carga múltiple de archivos
     * @returns bananaSettings para bananaImageManager
     */
    getImageBananaSettings: function (multiple) {
        var self = this;
        return {
            url: "index.php?r=banana/index",
            thumbsFolder: "../thumbs/",
            urlUpload: "index.php?r=banana/index",
            fileTypes: ['image'],
            multiple: multiple,
            token: csrfToken,
            tokenName: 'menacsrf',
            resetFolderOnStart: false,
            target: function (items) {
                if (self.hasFunction("bananaImageCallback")) {
                    self.getCurBlock().bananaImageCallback(items);
                }
                proB._resize();
            }

        }
    },
    /**
     * Función que se llama cuando se inicia el banana para la carga de archivos
     * no permite múltiple.
     * Se llama desde linkButton, los bloques que lo requieran y desde el plugin image de tinymce(function showDialog)
     * @param dest campo dónde se devuelve la url del archivo
     * @returns bananaSettings para bananaFileManager
     */
    getFileBananaSettings: function (dest) {
        var dataToReturn = {
            url: "index.php?r=banana/index",
            thumbsFolder: "../thumbs/",
            urlUpload: "index.php?r=banana/index",
            //fileTypes:['image','text','pdf'],
            token: csrfToken,
            tokenName: 'menacsrf',
            resetFolderOnStart: false,
            target: function (url) {
                if (dest == 'myfilemanager-inp') {
                    $('#' + dest).val(JSBlocks.utils.relativeToReal(url));
                } else if (dest == 'text_editor_ifr') {
                    var cont = '<iframe src="' + JSBlocks.utils.relativeToReal(url) + '" style="width:300px; height:500px;" frameborder="0"></iframe>';
                    var bef = tinyMCE.activeEditor.getContent();
                    tinyMCE.activeEditor.setContent(bef + cont);
                } else {
                    $("#" + dest + "_url").val(JSBlocks.utils.relativeToReal(url))
                }
            }
        };
        return dataToReturn;

    },
    /**
     * Función que se llama desde la función iconButton para obtener la configuración
     * del banana para la selección de iconos.
     * @param dest campo dónde se añade la clase del icono seleccionado
     * @returns bananaSettings para bananaFileManager
     */
    getIconBananaSettings: function (dest) {

        var self = this;
        return {
            url: "index.php?r=banana/index",
            thumbsFolder: "../thumbs/",
            urlUpload: "index.php?r=banana/index",
            iconsMode: true,
            iconsData: baseDir + '/icons.json',
            token: csrfToken,
            tokenName: 'menacsrf',
            resetFolderOnStart: false,
            target: function (iconClass) {
                if (self.hasFunction("bananaIconCallback")) {
                    self.getCurBlock().bananaIconCallback(iconClass, dest);
                } else {
                    $('#' + dest).find('i').removeClass().addClass(iconClass);
                }
            }
        }

    },
    /**
     * Función que devuelve el bloque actual sobre el que se está trabajando
     * @returns current Block
     */
    getCurBlock: function () {
        return this.blocks[this.currentBlock];
    },
    /**
     * Get current cms column structure
     * @returns current structure of cms
     */
    getCurrentTarget: function () {
        if (typeof(this.subrow) == 'undefined' || this.subrow == null) {
            return cms.data.structure[this.lang][this.row].content[this.col];
        } else {
            return cms.data.structure[this.lang][this.row].content[this.col].content[this.subrow];
        }
    },
    /**
     * Función que inicia la aplicación
     */
    init: function () {
        this.drawButtons();
        this.ready();
    },

    /**
     * Función que comprueba si exite en cada bloque la función ready y de ser así la ejecuta
     */
    ready: function () {
        $.each(this.blocks, function (k, v) {
            if (typeof v.ready == "function") {
                //JSBlocks.log("Ready fired in " + k);
                v.ready();
            }
        })
    },


    /**
     * Draw all the blocks buttons.
     */
    drawButtons: function () {
        var self = this;
        $.each(this.blocks, function (k, v) {

            if (!v.group) {
                self.log("The block " + k + " have not the property group defined", 'error');
                return false;
            }

            if (typeof v.getIcon == "function") {
                self.categories[v.group].push(v.getIcon())
            } else {
                self.categories[v.group].push(self._getButton(k));
            }

        });

        $.each(this.categories, function (kC, vC) {
            var grCont = self.getGroupButtonsContainer(kC);
            grCont.find('.eRow').append(vC);
            grCont.appendTo("#proBox .container");
        });


    }, getGroupButtonsContainer: function (group) {
        return $("<div>", {
            id: "proBox-" + group + "-group",
            html: $("<div>", {
                class: "eContainer",
                html: $("<div>", {
                    class: "row eRow eTypes",
                    html: $("<div>", {
                        class: "ProBoxBackButton col-xs-2",
                        click: function (e) {
                            $(window).scrollTop(cHtml.options.tempScrollTop);
                            cHtml.clearProBox('proBox-select');
                        }
                    })
                })
            })
        });
    },

    /**
     * Función que recupera el atributo icon de un bloque y devuelve el objeto jquery que corresponda
     * @param blockname nombre del bloque del que se va a crear el botón
     * @returns objeto jquery del botón del bloque en concreto
     * @private
     */
    _getButton: function (blockname) {

        var self = this;
        var block = this.blocks[blockname],
            iconstart = block.icon.substr(0, 2);
        if (iconstart == 'eI' || iconstart == 'fa') {
            //icon class
            content = $('<i>', {
                class: block.icon + " " + (iconstart == 'fa' ? "eIco" : "")
            });
        } else {
            //image
            content = $('<img>', {
                src: block.icon,
                class: 'img img-responsive'
            });
        }

        return $("<div>", {
            class: "eCol col-xs-2",
            id: "block_" + blockname,
            html: $("<div>",
                {
                    class: "ePanel",
                    html: [
                        $("<div>", {
                            class: "ePanelHeading",
                            text: block.name
                        }),
                        $("<div>", {
                            class: "ePanelContent",
                            html: content
                        })


                    ]

                })
        }).click(function (e) {
            self.getProbox(blockname);
        });
    },

    /**
     * Log messages of this class when debug is enabled
     * @param msg
     * @param type
     */
    log: function (msg, type) {
        if (this.debug) {
            if (!type) {
                type = "log";
            }
            console[type]("[JSBlocks] ", msg.split(","))

        }
    },

    /**
     * Check if block is configurable, if true calls block getProbox function.
     * If does not exist this function it treats like no configurable.
     * [block].getProbox must load data if exist, else clear inputs
     */
    getProbox: function (block) {
        //this.row = cms.options.work_in.row;
        //this.col = cms.options.work_in.col;
        this.currentBlock = block;
        //this.lang = cms.options.work_in.lang;
        //this.subrow=cms.options.work_in.part;

        if (this.getCurBlock().configurable) {

            this.loadData();
            if (this.hasFunction("getProbox")) {

                this.getCurBlock().getProbox();
            } else {
                cHtml.clearProBox('proBox-' + block);
                cHtml.openProBox(this.getCurBlock().name);

            }
            this.attachEvents();
            this.afterOpenProbox();

        } else this.addItem()


    },
    /**
     * Check if current block structure and data are right. If all it´s ok adds block data to cms structure.
     * If block data is empty or has some error it does not add item and if block data has error, it marks fields with error class
     * @returns boolean
     */
    addItem: function () {
        this.errors = [];
        if (this.getCurBlock().configurable) {
            this.collectData();
        }
        if (this.hasFunction('addItem')) {
            return this.getCurBlock().addItem();
        } else {
            if (this.checkBlockData() && this.errors.length === 0) {
                //this.log("Saving block data", "info");
                //EROS, lógica SPLITTED
                var colTarget = this.getCurrentTarget();

                if (colTarget.type == "splitted") {
                    var newColTarget = colTarget.content[this.subrow];
                    newColTarget.type = "" + this.currentBlock;
                    newColTarget.content = $.extend(true, {}, this.getCurBlock().data);

                } else {
                    colTarget.type = "" + this.currentBlock;
                    colTarget.content = $.extend(true, {}, this.getCurBlock().data);
                }
                return true;
            } else {
                if (this.errors.length === 0) {
                    this.log("Block data is empty", "info");
                    $.extend(true,
                        this.getCurrentTarget(),
                        {
                            type: "",
                            content: null
                        }//, true
                    );
                    return true;
                } else {
                    return false;
                }

            }
        }

    },
    /**
     * Fired from addItem.
     * Check if block data to add is empty
     * @returns {boolean} if it is correct true or false if block data is empty
     */
    checkData: function () {

        var self = this,
            modified = 0;

        if (this.getCurBlock().data) {
            $.each(this.getCurBlock().data, function (k, v) {

                if (typeof(v) == 'object') {
                    if ($.isEmptyObject(v)) {
                        modified += 0;
                    } else {
                        if (k == 'elink') {
                            modified += 1;
                        } else {
                            modified += self._checkData(k, v);
                        }
                    }
                } else {
                    modified += v.length
                }
            });
        }
        if (modified) {
            return true;
        } else {
            return false;
        }
    },
    /**
     * Fired form checkData()
     * check data from array of items
     * @param key array object key. The param name of the object item.
     * @param obj array of items to check
     * @returns modified value
     * @private
     */
    _checkData: function (key, obj) {
        var modified = 0;
        $.each(obj, function (k, v) {
            if (typeof v == "object") {
                if ($.isEmptyObject(v)) {

                } else {
                    if (key == 'elink') {
                        modified += 1;
                    } else {
                        //var result = JSBlocks._checkData(v);
                        modified += 1;//result;
                    }
                }
            } else {
                modified += parseInt(v.length)
            }
        });
        return modified;
    },
    /**
     * Fired from loadData if the block is fired for first time (it has not content)
     * Copy basic data of current block to data object in current block (temp var)
     */
    copyBasicData: function () {
        var cb = this.getCurBlock();
        var self = this;

        cb.data = {};
        $.each(cb.dataStructure, function (k, v) {


            if (self.checkParamType(k) == "object") {

                cb.data[k] = [];
            } else {
                cb.data[k] = null;
            }
        });

    },
    /**
     * @todo: Create col structure must set content to null by default
     * Fired from getProbox()
     * Loads block data to currentBlock data property (temp var).
     * If block has not content already defined, copyBasicData function is fired;
     */
    loadData: function () {
        if (this.getCurrentTarget().content) {
            this.getCurBlock().data = $.extend(true, {}, this.getCurrentTarget().content)
        } else {
            this.copyBasicData();
        }
        if (this.hasFunction("parseLoadedData")) {
            this.getCurBlock().parseLoadedData();
        } else {
            this._loadData();
        }
    },
    /**
     * Parse the data loaded and set values in form inputs. This function is
     * ignored if exists parseLoadedData in block class.
     * @private
     */
    _loadData: function () {
        //@todo: Make better fill of form elements.
        var cb = this.getCurBlock();
        var self = this;

        $.each(this.getCurBlock().data, function (k, v) {

            if (!v) {
                return;
            }
            if (self.hasFunction("load" + k)) {
                self.log("Calling load" + k, "info");
                self.blocks[self.currentBlock]['load' + k](v);
            } else {
                self.log("load" + k + "() is not present. Using default collector");
                var selector = "#" + cb.name + "_" + k;

                if (self.checkParamType(k) == "object") {
                    //@todo: ver con Eros si está bien porque en el momento que detecta un objeto(en el caso de la imagen el elink) llama a la función.
                    if (k != 'elink') {
                        self._loadArrayData(k);
                    }
                } else {
                    var el = $(selector);
                    self.log(el);
                    if (el.length) {
                        self._setInputValue(el, v);
                    } else {
                        self.log(selector + " not found", 'warn')

                    }
                }

            }

        });
    },
    /**
     *
     * @param param
     * @private
     */
    _loadArrayData: function (param) {


        var cb = this.getCurBlock();

        var cont = $('#' + cb.name + '_' + param);
        $.each(cb.data[param], function (k, v) {
            var li = $('<li>', {
                class: 'list-group-item ' + cb.name + '_' + param
            }).appendTo(cont);
            var html = cb['get' + param + 'licontent'](k, v, li);

            li.html(html);
        });
    },
    /**
     * Fired from _collectData and _collectObjectData
     * Gets input value of param, get block param validator and validate it. If it is not a valid value adds error class to input.
     * @param param name of param to get it back
     * @param key Only required if the param is from an item of array

     * @returns value input value;
     * @private
     */
    _validateAndGet: function (param, key) {

        var sel = param + (typeof (key) != 'undefined' && key !== "" ? '_' + key : '');
        var value = this._getInputValue(sel);


        if (typeof (this.getCurBlock().dataStructure[param]) != 'undefined' && (typeof (this.getCurBlock().dataStructure[param].validator) != 'undefined' || typeof (this.getCurBlock().dataStructure[param].required) != 'undefined'))  {
            var validator = typeof this.getCurBlock().dataStructure[param].validator!= 'undefined'? this.getCurBlock().dataStructure[param].validator:false;
            var required= typeof this.getCurBlock().dataStructure[param].required!= 'undefined'? this.getCurBlock().dataStructure[param].required:false;
            if (!this._validate(validator,required, param, value)) {

                var elm = $('#' + this.currentBlock +"_"+ sel);
                this.setErrorClass('add', elm, param);
            }
        }
        return value;

    },
    /**
     * Fired from _validateAndGet
     *
     * @param type validator of block param to check
     * @param param param name to validate
     * @param value in the form input

     * @returns {boolean} true if it is a valid value, false if it is not.
     * @private
     */
    _validate: function (validator,required, param, value) {
        var check_ok = true;
        if(validator!=false) {
            switch (validator) {
                case 'number':
                    check_ok = true;
                    break;
                case 'int':
                    check_ok = true;
                    break;
                case 'float':
                    check_ok = true;
                    break;
                case 'string':
                    check_ok = true;
                    value = value.trim();
                    break;
                case 'boolean':
                    check_ok = true;
                    break;
                case 'url':
                    check_ok = true;
                    break;
                case 'date':
                    check_ok = true;
                    break;
                case 'own':
                    if (this.hasFunction('validate' + param)) {
                        check_ok = this.getCurBlock()['validate' + param](value);
                    } else {
                        check_ok = true;
                    }
                    break;

            }
            if (!check_ok) {
                this.errors.push('El parámetro ' + param + ' no es valido. Debe ser ' + validator + '.');
            }
        }
        if(required!=false) {
            if (this.getCurBlock().dataStructure[param].required) {

                if (param == 'elink') {
                    if (value.type == 'none') {
                        check_ok = false;
                        this.errors.push('El parámetro ' + param + ' es requerido.');
                    }
                } else {
                    if (value == "") {

                        check_ok = false;
                        this.errors.push('El parámetro ' + param + ' es requerido.');
                    }
                }
            }
        }
        return check_ok;
    },
    /**
     * Check type of param. It works on current block.
     * @param param name of the param for check type
     * @returns {string} type of param. It can be object or string
     */
    checkParamType: function (param) {
        var self = this;
        var paramData = this.popCommonOptions(param);

        if ($.isEmptyObject(paramData)) {
            return 'string';
        } else {
            return 'object';
        }
    },
    /**
     * Function which return dataStructure elements without required and validator options.
     * It works on current block.
     * @param param name of the param which are looking for structure
     * @returns {*} object like dataStructure cleaned
     */
    popCommonOptions: function (param) {
        var self = this;
        var dS = $.extend(true, {}, self.getCurBlock().dataStructure[param]);
        $.each(dS, function (k, v) {
            if (k == 'required' || k == 'validator') {
                delete dS[k];
            }
        });
        return dS;
    },
    /**
     * Sets the value for an input. If null sets as empty
     * @param el jQuery element
     * @param value Value
     * @private
     */
    _setInputValue: function (el, value) {
        switch (el.prop("tagName")) {
            case "INPUT":
                if (el.attr('type') == "checkbox") {
                    el.prop("checked", (value ? true : false));
                } else {
                    el.val(value ? value : "");
                }
                break;
            case "SPAN":
                el.html(value);
                break;
            case "TEXTAREA":
            case "SELECT":
                el.val(value);
                break;
            default:
                this.log("Unrecognized tag type " + el.prop("tagName") + " - < This was the tag", 'warn');
                break;

        }

    },

    /**
     * Autodetect and returns the value of an input
     * @param el jQuery valid Selector
     * @returns {*} Value of the item.
     * @private
     */
    _getInputValue: function (key) {
        var el = $('#' + this.currentBlock + '_' + key);

        switch (el.prop("tagName")) {
            case "DIV":
                if (el.prop('class').indexOf('_elink') != -1) {
                    return this.getLinkData(el);
                }
                if (el.prop('class').indexOf('_eicon') != -1) {
                    return el.find('i').prop('class');
                }
                break;
            case "INPUT":
                if (el.attr('type') == "checkbox") {
                    //this.log(["Type checkbox", el.val()]);
                    return el.prop("checked") ? "1" : null;
                } else {
                    return el.val();
                }
                break;

            case "TEXTAREA":
            case "SELECT":
                return el.val();
                break;
            case "SPAN":
                return el.html().trim();
                break;
            default:
                this.log("Unrecognized tag type " + el.prop("tagName") + " - < This was the tag", 'warn');
                return null;
                break;
        }
    },
    /**
     * Add or remove error class of param element
     * @param action 'add' or 'remove'
     * @param el element with error
     */
    setErrorClass: function (action, el, param) {

        if (this.hasFunction("setErrorClass" + param)) {
            this.getCurBlock()['setErrorClass' + param](action, el);
        }

        if (typeof el.attr('class') == "undefined") {
            console.error("Element is not as expected");
            return;
        }
        if (el.attr('class').indexOf('_elink') != -1) {
            el = $(el).find('.' + this.currentBlock + '_elink_type');
        }
        switch (action) {
            case 'add':
                if (el.closest('.form-group').length > 0) {
                    el.closest('.form-group').addClass('has-error');
                } else {
                    el.addClass('error');
                }
                break;
            case 'remove':
                if (el.closest('.form-group').length > 0) {
                    el.closest('.form-group').removeClass('has-error');
                } else {
                    el.removeClass('error');
                }
                break;
        }
    },
    /**
     * Fired from addItem()
     * check if exits collectData function in current block. If exits fired it,
     * but if it does not exist, function fired _collectData
     *
     */
    collectData: function () {
        if (this.hasFunction('collectData')) {
            this.getCurBlock().collectData();
        } else {
            this._collectData()
        }
    },
    /**
     * Fired from collectData
     * Fill current block data properties with valid values in form inputs.
     * @private
     */
    _collectData: function () {
        var cb = this.getCurBlock();
        var self = this;

        $.each(this.getCurBlock().dataStructure, function (k, v) {

            self.log("Start collecting data", "info");

            if (self.hasFunction("collect" + k)) {
                self.log("Calling collect" + k + "() of " + self.currentBlock, "info");

                cb['collect' + k](v);
            } else {
                var selector = "#" + cb.name + "_" + k;

                if (self.checkParamType(k) == "object") {
                    self.log("Es un objeto");
                    self._collectObjectData(cb, selector, k);
                } else {
                    self.log("Pidiendo " + k);
                    //clearError class
                    self.setErrorClass('remove', $(selector), k);
                    cb.data[k] = self._validateAndGet(k);
                }
            }
        });
    },
    /**
     * Fired from _collectData
     * Fill param array with each item values;
     * @param cb currentBlock
     * @param selector of input form
     * @param k key of array item
     * @private
     */
    _collectObjectData: function (cb, selector, k) {
        var self = this;
        var select_for_id = selector.replace('#', '').replace('.', '');

        var itemStructure = this.popCommonOptions(k);

        cb.data[k] = [];
        $.each($("." + select_for_id + ""), function (kb, vb) {
            cb.data[k][kb] = {};
            $.each(itemStructure, function (ki, vi) {
                var el = $(vb).find("."+ cb.name+"_"+k+"_" + ki);

                self.setErrorClass('remove', el, ki);

                var key = el.prop('id');
                key = key.replace(cb.name+"_"+k + "_" + ki + '_', '');
                cb.data[k][kb][ki] = self._validateAndGet(k + "_" + ki, key);

            });
        });

    },
    /**
     * Check wether a function exists in block.
     * @param name The name of the function
     * @returns {boolean}
     */
    hasFunction: function (name) {

        return typeof this.getCurBlock()[name] == "function";
    },
    /**

     * Clear probox form inputs and current block data property.
     */
    clear: function () {
        this.log("Cleaning", 'info');
        var self = this,
            cb = this.getCurBlock();

        if (cb == null) {
            return;
        }
        $.each(cb.dataStructure, function (k, v) {

            if (self.hasFunction("clear" + k)) {
                cb['clear' + k](v);
            } else {
                var selector = cb.name + "_" + k;

                if (self.checkParamType(k) == "object") {
                    $.each($("[id^=" + selector + "]"), function (kb, vb) {
                        self._setInputValue($(vb), null);
                    });
                } else {
                    //@todo: ver con Eros ya que al realizar el coambio de definición de los parámetros tengo que poner el valor null
                    //@todo:---> si no muestra [object Object] en el campo de texto
                    self._setInputValue($("#" +selector), null);
                }
            }
        });
        cb.data = null;
    },
    /**
     * Fired from getProbox();
     * Check if current block has events to attach.
     * If block has events this function attach them and then attach common events.
     * Before attach events this function does unbind method for prevent duplicity
     */
    attachEvents: function () {
        var cb = this.getCurBlock();
        if (cb.events) {
            $.each(cb.events, function (event, data) {
                $.each(data, function (k, binds) {
                    $(binds.el).off(event).on(event, binds.ck);
                })
            });
        } else {
            this.log("The block has not events to bind.", 'warn');
        }
        this._commonEvents();
    },
    /**
     * Fired from attachEvents()
     * This function first unbind and then bind common events for all blocks
     * @private
     */
    _commonEvents: function () {
        //@todo: This should be better coded
        var self = this;
        self.log("Attaching common events", "info");
        $(".proBox-save").unbind('click').bind('click', function () {
            self.beforeSaveBlockData();
        });

    },
    _saveItem: function () {
        if (this.addItem()) {
            this.log("Item added,closing probox", "info");
            var close = this.beforeCloseProbox();
            var self = this;
            if (close) {
                proB.hide(function () {
                    self.clear();
                    self.currentBlock = null;
                    cHtml.drawCms();
                });
            }
        }
    },
    /**
     * Fired from add item. It checks if exists checkData function on current block.
     * If exits fired it (the function on the block must return true -or false-), if not returns JSBlocks default check data function;
     * @returns {boolean}
     */
    checkBlockData: function () {
        if (this.hasFunction('checkData')) {
            return this.getCurBlock().checkData();
        } else {
            return this.checkData();//return true;
        }
    },
    /**
     * Fired just before to add item. It checks if exists beforeSave function on current block.
     * If exits fired it (the function on the block if true result must call to _saveItem function), if not returns true;
     *
     * @returns {boolean}
     */
    beforeSaveBlockData: function () {
        if (this.hasFunction('beforeSave')) {
            return this.getCurBlock().beforeSave();
        } else this._saveItem();//return true;
    },
    /**
     * Fired form getProbox(), after open probox.
     * Check if exits afterOpen function in current block.
     * If exits fired it , if not returns true;
     * @returns {*}
     */
    afterOpenProbox: function () {
        if (this.hasFunction('afterOpen')) {
            return this.getCurBlock().afterOpen();
        } else return true;
    },
    /**
     * Function fired when user clicks in probox-save button
     * It checks if exists beforeClose function on current block.
     * If exits fired it (the function on the block must return true -or false-), if not returns true;
     * @returns {*}
     */
    beforeCloseProbox: function () {
        if (this.hasFunction('beforeClose')) {
            return this.getCurBlock().beforeClose();
        } else return true;
    },
    /**
     *
     * Create icon button selector
     * @param block name of the block which call function for create
     * @param param name of the param associated (normally eicon)
     * @param index required only if param is array of items. Key of item in the array
     * @param content content of the param. It can be '' if param has not content
     * @param container container to append icon button
     * @param append if we want append button directly or return button html for append in other place
     * @returns {*|jQuery|HTMLElement} icon Button element
     */
    iconButton: function (block, param, index, content, container, append) {

        var with_cont = true;
        if (content == '' || typeof(content) == 'undefined' || typeof(content.eicon) == 'undefined' || content.eicon == null) {
            with_cont = false;
        }
        var target = block + "_" + param + (index !== '' ? '_' + index : '');
        var icon = $('<i>', {
            id: target + '_icon',
            class: (with_cont ? content.eicon : 'fa fa-circle')
        });
        if (append) {
            icon.appendTo(container);
        }
        var settings="";
        if (typeof JSBlocks.blocks[block]["getIconBananaSettings"] == "function") {
            settings=JSBlocks.blocks[block]["getIconBananaSettings"](target);
        }else{
            settings=JSBlocks.getIconBananaSettings(target);
        }
        container.banana(settings);
        if (!append) {
            return icon;
        }
    },
    /**
     * Fired from blocks, normaly in function afterOpenProbox to set value into iconButton element
     * @param selector id of the icon element
     * @param iconClass class to set into icon element
     */
    setIconData: function (selector, iconClass) {
        if (typeof(iconClass) == 'undefined' || iconClass == null) {
            iconClass = 'fa fa-circle';
        }
        $('#' + selector + ' > i').removeClass().addClass(iconClass);
    },
    /**
     * Fired from ajax-engine.js, when ajax response include availablePages var, for refresh eLink page selector
     */
    refreshAvailablePages: function () {
        $('select[class*="_elink_page"]').html('');
        $.each(cms.availablePages, function (k, v) {
            $("<option>", {
                text: v.name+(v.published?'':'*'),
                value: v.id
            }).attr('data-url', v.url).appendTo('select[class*="_elink_page"]');
        });
    },
    /**
     * Create linkButton element
     * @param block name of the block which call function for create
     * @param param name of the param associated (normally elink)
     * @param index
     * @param content content of the param. It can be '' if param has not content
     * @param container container to append link button
     * @param append if we want append button directly or return button html for append in other place
     * @returns {*|jQuery|HTMLElement} linkButton element
     */
    linkButton: function (block, param, index, content, container, append) {

        var with_cont = true;
        if (content == '' || typeof(content) == 'undefined' || typeof(content.elink) == 'undefined') {
            with_cont = false;
        }
        var self = this;

        var types = {
            none: "Link: none",
            page: "Page link",
            url:"Link to url",
            file:"Link to file"
        };

        //switch newPage
        var target = block + "_" + param + (index !== '' ? '_' + index : '');
        var cls = block + "_" + param;


        var switchNewPage = $("<div>", {
            class: "onoffswitch pull-right"
        });

        var chkNewPage = $("<input>", {
            type: "checkbox",
            style: "display:none",
            class: "onoffswitch-checkbox chk_page_blank " + cls + "_newpage",
            name: 'onoffswitch',
            id: target + "_newpage"
        }).appendTo(switchNewPage).prop('checked', (with_cont ? content.elink.newpage : false));

        var lblNewPage = $("<label>", {
            type: "text",
            class: "onoffswitch-label",
            for: target + "_newpage"
        }).appendTo(switchNewPage);


        var btnNewPage = $("<div>", {
            class: cls + "_btnNewPage_cont elinkTarget",
            //style:"display:block",
            id: target + "_btnNewPage_cont"

        });

        var inputAddon = $("<div>", {
            class: block + "_elink_el " + "input-group-addon",
            style: "display:none",
            html: btnNewPage
        });

        var textNewPage = $("<icon>", {
            class: "glyphicon glyphicon-new-window"
        }).appendTo(btnNewPage);
        switchNewPage.appendTo(btnNewPage);

        //Page selector
        var selectPage = $("<select>", {
            class: block + "_elink_el form-control " + cls + "_page",
            style: "display:none",
            id: target + "_page",
            change: function (e) {
                var page = $(this).val();
                var url = $(this).find(":selected").data('url');
                $("#" + target + "_url").val(JSBlocks.utils.relativeToReal(url))
            }
        }).val((with_cont ? (content.elink.type == 'page' ? content.elink.page : '') : ''));

        $.each(cms.availablePages, function (k, v) {
            var opt = $("<option>", {
                text: v.name+(v.published?'':'*'),
                value: v.id
            }).attr('data-url', v.url).appendTo(selectPage);
        });


        //File selector
        var contFile = $("<div>", {
            class: "input-group " + block + "_elink_el",
            style: "display:none"

        });
        var btnFile = $("<span>", {
            class: "input-group-btn " + block + "_elink_el",
            style: "display:none"

        });

        var buttFile = $("<button>", {
            class: "btn btn-secondary " + cls + "_file_browser",
            type: "button",
            id: target + "_file_browser",
            html: '<i class="fa fa-search"></i>'
        }).appendTo(btnFile);

        contFile.append(btnFile);


        // Url input
        var urlInput = $("<input>", {
            type: "text",
            class: "form-control " + cls + "_url",
            id: target + "_url"
        }).appendTo(contFile).val((with_cont ? (content.elink.type != 'none' ? content.elink.url : '') : ''));

        var type = (with_cont ? content.elink.type : 'none');
        //Type selector
        var selectType = $("<select>", {
            class: "form-control " + cls + "_type",
            id: target + "_type",
            change: function (e) {

                var selected = $(this).val();
                container.find("." + block + "_elink_el").hide();
                urlInput.val('');
                switch (selected) {
                    case "page":
                        selectPage.show();
                        inputAddon.show();
                        break;

                    case "url":
                        contFile.show();
                        inputAddon.show();
                        break;

                    case "file":
                        contFile.show();
                        btnFile.show();
                        inputAddon.show();
                        break;
                }
            }
        });

        var inputGroup = $("<div>", {
            class: 'input-group ',
            html: [selectType,
                inputAddon]
        });

        $.each(types, function (k, v) {
            $("<option/>", {
                text: v,
                value: k
            }).appendTo(selectType);

        });

        //var col1=$("<div>",{
        //    class:'col-sm-7',
        //    //html:[selectType]
        //});
        var col2 = $("<div>", {
            class: 'col-md-12',
            //html:[btnNewPage]
            html: [inputGroup]
        });
        var row1 = $("<div>", {
            class: 'row',
            html: [col2]
        });
        var col3 = $("<div>", {
            class: 'col-md-12',
            html: [contFile, selectPage]
        });
        var row2 = $("<div>", {
            class: 'row',
            html: col3

        });
        var linkContainer = $("<div>", {
            class: block + "_elink",
            html: [row1, row2]
        });

        if (append) {
            linkContainer.appendTo("#" + target);
        }
        $(buttFile).banana(JSBlocks.getFileBananaSettings(target));


        //@todo:  Le pongo el set Timeout porque si no no me hace caso
        setTimeout(function () {
            //container.find("."+block+"_elink_el").hide();
            selectType.val(type);
            chkNewPage.prop('checked', false);
        }, 30);
        setTimeout(function () {
            switch (type) {
                case "page":
                    selectPage.val(content.elink.page);
                    setTimeout(function () {
                        selectPage.show();
                    }, 150);
                    break;
                case "url":
                    contFile.show();
                    break;
                case "file":
                    contFile.show();
                    btnFile.show();
                    break;
            }
            if (type != 'none') {
                //btnNewPage.show();
                inputAddon.show();
                if (content.elink.newpage == 1) {
                    btnNewPage.find('.onoffswitch-checkbox').prop('checked', true);
                }
            }
        }, 50);//@todo: VEr con Eros si el tiempo del retraso está bien

        if (!append) {
            return linkContainer;
        }
    },
    /**
     * Fired from blocks, normaly in function afterOpenProbox to set value into linkButton element
     * @param block name of the block which call function
     * @param param name of the param associated (normally elink)
     * @param content data of elink property
     * @param container JQUERY element which contains linkButton
     */
    setLinkData: function (block, param, content, container) {
        var prefix = '.';

        var target = block + "_" + param;

        var urlInput = container.find(prefix + target + "_url");

        var urlInputCont = urlInput.closest('.input-group');
        var btnBrowseFile = urlInputCont.find('.input-group-btn');
        var selectType = container.find(prefix + target + "_type");
        var selectPage = container.find(prefix + target + "_page");
        var btnNewPage = container.find(prefix + target + "_btnNewPage_cont");
        container.find("." + block + "_elink_el").hide();
        if (content != null && content.elink != null) {

            var type = content.elink.type;

            if (type == null) {
                type = 'none';
            }
            selectType.val(type);
            switch (type) {
                case "page":
                    selectPage.val(content.elink.page);
                    setTimeout(function () {
                        selectPage.show();
                    }, 50);
                    break;
                case "url":
                    urlInputCont.show();
                    break;
                case "file":
                    btnBrowseFile.show();
                    urlInputCont.show();
                    break;
            }
            btnNewPage.find('.onoffswitch-checkbox').prop('checked', false);
            if (type != 'none') {
                urlInput.val(content.elink.url);
                btnNewPage.show();
                if (content.elink.newpage == 1) {
                    btnNewPage.find('.onoffswitch-checkbox').prop('checked', true);
                }
            }
        } else {
            selectType.val('none');
        }
    },
    /**
     * Fired from _getInputValue function when the element to get data is a elink.
     * @param container JQUERY element which is the container of linkButton
     * @returns object which contains linkButton data like this {{type: *, url: *, page: *, newpage: *}}
     */
    getLinkData: function (container) {

        var type = container.find('[class*="_elink_type"]').val();
        var selectPage = container.find('[class*="_elink_page"]');
        var urlInput = container.find('[class*="_elink_url"]');
        var btnNewPage = container.find('[class*="_elink_btnNewPage_cont"]').find('.onoffswitch-checkbox');
        var url, page, newpage;
        switch (type) {
            case "page":
                page = selectPage.val();
                url = urlInput.val().trim();
                newpage = btnNewPage.prop('checked');
                break;
            case "url":
                url = urlInput.val().trim();
                page = '';
                newpage = btnNewPage.prop('checked');
                break;
            case "file":
                page = '';
                url = urlInput.val().trim();
                newpage = btnNewPage.prop('checked');
                break;
            case "none":
                page = null;
                url = null;
                newpage = false;
                break;
        }
        if (newpage) {
            newpage = '1';
        } else {
            newpage = '0';
        }
        return {
            type: type,
            url: url,
            page: page,
            newpage: newpage
        }
    },
    /***** UTILS *****/
    utils: {
        /**
         * Converts relative path to real path
         * @param path relative path to convert to real path
         * @returns real path from param path
         */
        relativeToReal: function (path) {
            if (path.indexOf("http") < 0 && path.indexOf("../") == 0) {
                return path.substr(3);
            } else {
                return path;
            }
        },
        /**
         * Converts real path to relative path
         * @param path real path to convert to relative path
         * @returns relative path from param path
         */
        realToRelative: function (path) {
            if (path.indexOf("http") < 0 && path.indexOf("../") != 0) {
                return "../" + path;
            } else
                return path;
        },
        getThumbUrl: function (path) {
            return path.replace("upload", "thumbs/upload")
        }

    }

};