/**
 * MenaPRO
 *
 * cHtml class.
 *
 * This class is responsible of creating all html needed for
 * control panel.
 *
 * Dependencies: JSBlocks - cms
 *
 */


var cHtml = {
    options: {
        tempScrollTop: 0,
        debug: false
    },
    /**
     * Log messages of this class when debug is enabled
     * @param msg
     * @param type
     */
    log: {
        info:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"info");
            })
        },warn:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"warn");
            })
        },
        error:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"error");
            })
        },
        log: function (msg, type) {
            if (cHtml.options.debug) {
                if (!type) {
                    type = "log";
                }
                if(typeof msg=="string")
                {

                    console[type]("%c[CHTML]" +(type=="error"?"%c":"") +msg,'background:#a900cc; color: #fff',(type=="error"?'background:#f00; color: #fff':""))
                }else
                {
                    console.group("[CHTML · Detailed object] ");
                    console[type](msg);
                    console.groupEnd();
                }

            }
        }
    },
    /**
     * Returns empty eRow
     * @returns {*|jQuery|HTMLElement}
     */
    getRow: function () {
        var row = $('<div>', {
            class: 'row eRow'
        });
        return row;
    },
    /**
     * Return the gutter row with all column combinations. It is used
     * between rows. To view this hover between rows 1 second. Animation is made with css.
     * @param k
     * @returns {*|jQuery|HTMLElement}
     */
    getGutterRow: function (k) {
        var addR = $('#clonable-eColumns').clone();
        addR.attr('id', '');
        addR.addClass('eGutterColumns');
        var row = $('<div>', {
            class: 'eGutter',
            id: 'gR_' + (k + 1)
        });
        addR.delegate('.col-xs-2', 'click', function (e) {
            cHtml.options.tempScrollTop = $(window).scrollTop();
            var index = $(e.currentTarget).closest('.eGutter').attr('id');
            index = index.replace('gR_', '');
            var row = index;

            var p = $(this).data('col').toString().split(',');

            cms.createCmsJson(p, row, index);
        });
        addR.appendTo(row);
        return row;
    },
    /**
     * Return an empty column with twitter bootstrap class
     * @param nClass
     * @returns {*|jQuery}
     */
    getCol: function (nClass) {
        var col = $('#clonable-eCol').clone();
        col.removeClass('hidden').attr('id', '').addClass('col-xs-' + nClass);
        return col;
    },
    /**
     * Returns new column creation button.
     * @param nClass     integer       twitter bootstrap number
     * @param row        integer       index of row
     * @param kcol       integer       index of column
     * @param splitted   boolean       type of column
     * @param subrowK    integer       index of splitted row
     * @returns {*|jQuery}
     */
    getNewCol: function (nClass, row, kcol, splitted, subrowK) {
        if (typeof splitted != "undefined") {

            nClass = "12 splitted";
        }
        if (typeof subrowK == "undefined") {
            subrowK = null;
        }
        var col = cHtml.getCol(nClass);
        col.find('.ePreview').addClass('eAddContent');
        col.find('.ePanel').click(function (e) {
            cHtml.openSelection(row, kcol, subrowK);
        });
        if(splitted) {
            var btnDelTrash = $('<span>', {
                class: 'eTrashBtn',
                html: '<i class="fa fa-trash"></i>',
                click: function (e) {
                    e.stopPropagation();
                    JSBlocks.row = row;
                    JSBlocks.col = kcol;
                    JSBlocks.subrow = subrowK;
                    cHtml.options.tempScrollTop = $(window).scrollTop();
                    cms.toTrash();

                }

            }).appendTo(col.find('.ePanel'));
            col.find('.ePanel').addClass('eSplitted');
        }
        return col;
    },
    /**
     * Opens proBox with group selection
     * @param row
     * @param kcol
     * @param subrowK
     */
    openSelection: function (row, kcol, subrowK) {

        JSBlocks.row = row;
        JSBlocks.col = kcol;
        JSBlocks.subrow = subrowK;

        cHtml.clearProBox('proBox-select');
        cHtml.openProBox('selection');
    },
    /**
     * Returns html object to use as block title
     * @param type
     * @returns {*|jQuery}
     */
    getPreviewTitle: function (type) {
        var t = $('<span class="ePreviewTitle">', {}).html(type.toUpperCase());
        return $(t).outerHTML();
    },

    /**
     * Opens proBox and show the group selected
     * @param type
     */
    openProBox: function (type) {

        proB.show();

        var col_class = JSBlocks.getCurrentTarget();// cms.data.structure[cms.options.work_in.lang][JSBlocks.row].content[cms.options.work_in.col].class;
        $.each(JSBlocks.blocks, function (k, v) {
            $('#block_' + v.name).removeClass('hidden');
            if (typeof(JSBlocks.blocks[v.name].not_allowed) != "undefined") {
                $.each(JSBlocks.blocks[v.name].not_allowed, function (q, b) {
                    if (b == col_class) {
                        $('#block_' + v.name).addClass('hidden');
                    }
                });
            }else
            {
                //cHtml.log.error("Could not read property allowed from block "+ v.name)
            }
        });


        if (type != '' && type != 'selection') {


        }


    },
    /**
     * Hides all panels except the one passed by params
     * @param id
     */
    clearProBox: function (id) {
        $(proB.uid + " div[id*='proBox-']").not("#" + id).hide();
        $("#" + id).show();
        this.log.info("Clearing proBox, except: "+id);
        proB._resize();
    },
    /**
     * Compares between latest saved content and actual content.
     * If there are differences sends data to save.
     */
    checkChanges: function () {

        if (cms.options.last_copy != JSON.stringify(cms.data)) {
            if (cms.options.autosave) {
                cp.saveContent();
            }
            cms.options.hasChanges = true;
            cms.options.last_copy = JSON.stringify(cms.data);
        }else{
            this.log.info("Content is the same as before. Ignoring save")
        }


    },
    /**
     * Renders a row based in structure.
     * @param k {integer}   Index of row
     * @param v {integer}   Content of row
     * @returns {string}
     * @private
     */
    _renderRow: function (k, v) {
        var row = '';
        if (typeof(v) != 'undefined' && typeof(v.content) != 'undefined' && v.content.length > 0) {

            row = cHtml.getRow().attr('id', 'row_' + k);
            var hand = $('#clonable-eHandler').clone();
            hand.attr('id', '').removeClass('hidden');
            var opt = $('#clonable-row-options').clone();
            opt.attr('id', '').removeClass('hidden');
            opt.find('.eRowDeleteBtn').click(function (e) {
                cms.trashRow(k);

            });

            var del = $('#clonable-trash-row').clone().attr('id', '').removeClass('hidden').click(function (e) {
                row.addClass('config');
            });

            row.click(function (e) {
                JSBlocks.row = k;
            });

            row.append(hand, opt, del);

            opt.find('.selHtmlOptions').change(function (e) {

                var input = opt.find('.customClass');
                if ($(this).val() == 'eCustom') {
                    input.removeClass('hidden').val('');
                    input.focus();
                } else {
                    //Add an animation in css to hide and show
                    input.addClass('hidden');
                    cms.addRowHtmlOptions($(this).val(),k);
                    $(this).val() == '' ? del.removeClass('eOptionsEdited') : del.addClass('eOptionsEdited');
                    cHtml.checkChanges();
                }
            });
            opt.find('.customClass').change(function (e) {
                var txtClass = opt.find('.customClass').val().trim();
                if (txtClass != "") {
                    cms.addRowHtmlOptions(txtClass);
                    del.addClass('eOptionsEdited');
                    cHtml.checkChanges();
                }
            });
            opt.find('.customClass').keyup(function (e) {
                if (e.which == 13) {
                    var txtClass = opt.find('.customClass').val().trim();
                    if (txtClass != "") {
                        cms.addRowHtmlOptions(txtClass);
                        del.addClass('eOptionsEdited');
                        cHtml.checkChanges();
                    }
                }
            });
            if (typeof(v.htmlOptions) != 'undefined' && typeof(v.htmlOptions.rowClass) != 'undefined' && v.htmlOptions.rowClass != "") {
                var rowClass = v.htmlOptions.rowClass;
                if (typeof(cms.options.rowOptionsDefault[rowClass]) != 'undefined' || typeof(cms.options.rowOptions[rowClass]) != 'undefined') {
                    opt.find('.selHtmlOptions').val(rowClass);
                } else {
                    opt.find('.selHtmlOptions').val('eCustom');
                    opt.find('.customClass').removeClass('hidden').val(rowClass);
                }
                del.addClass('eOptionsEdited');
            }
            // Start iterating rows
            $.each(v.content, function (colK, colV) {
                row.append(cHtml._preRenderCol(k, colK, colV));
            });
            return row;
        }
    },
    _preRenderCol: function (k, colK, colV, subrowK, splitted) {
        if (colV.type == '') {
            col = cHtml.getNewCol(colV.class, k, colK, splitted, subrowK);
            cHtml.attachDragCol(col, k, colK, subrowK);
            cHtml.attachDraggable(col,k,colK,subrowK);
            return col;
        } else if (colV.type == "splitted") {
            var col = $("<div>", {
                class: "eCol col-xs-" + colV.class + " splitted",
                html: [
                    $("<div>", {
                        class: "eHandlerCol",
                        html: [
                            $("<span>"),
                            $("<span>"),
                            $("<span>"),
                        ]
                    }),
                    $("<div>", {
                        class: "row",
                        html: [
                            cHtml._preRenderCol(k, colK, colV.content[0], 0, true)
                        ]
                    }), $("<div>", {
                        class: "row",
                        html: [

                            cHtml._preRenderCol(k, colK, colV.content[1], 1, true)

                        ]

                    })]

            });

            cHtml.attachDragCol(col, k, colK, subrowK);

            return col;
        } else {
            return cHtml._renderCol(k, colK, colV, subrowK, splitted);
        }
    },

    /**
     * Renders column preview based on structure
     * @param k
     * @param colK
     * @param colV
     * @param subrowK
     * @param splitted
     * @returns {*|jQuery}
     * @private
     */
    _renderCol: function (k, colK, colV, subrowK, splitted) {
        if (typeof splitted == "undefined") {
            splitted = null;
        }
        if (typeof subrowK == "undefined") {
            subrowK = null;
        }

        var col = cHtml.getCol(splitted ? "12" : colV.class);
        if (subrowK != null) {
            col.find('.eHandlerCol').remove();
        }
        cHtml.attachDragCol(col, k, colK, subrowK);
        cHtml.attachDraggable(col, k, colK, subrowK);

        if (subrowK != null) {
        }
        var btnDelTrash = $('<span>', {
            class: 'eTrashBtn',
            html: '<i class="fa fa-trash"></i>',
            click: function (e) {
                e.stopPropagation();
                JSBlocks.row = k;
                JSBlocks.col = colK;
                JSBlocks.subrow = subrowK;
                cHtml.options.tempScrollTop = $(window).scrollTop();

                cms.toTrash();

            }

        }).appendTo(col.find('.ePanel'));

        if (splitted == null && colV.class != "12") {
            var btnSplit = $('<span>', {
                class: 'eSplitBtn',
                html: '<i class="fa fa-clone"></i>',
                click: function (e) {
                    e.stopPropagation();
                    JSBlocks.row = k;
                    JSBlocks.col = colK;
                    //JSBlocks.lang = cms.options.work_in.lang;
                    var col = JSBlocks.getCurrentTarget();
                    var content = $.extend(true, {}, JSBlocks.getCurrentTarget());
                    col.type = "splitted";
                    col.content = [];
                    col.content.push(content);
                    col.content.push({
                        type: "",
                        class: 12,
                        content: null
                    });
                    cHtml.drawCms();
                }
            }).appendTo(col.find('.ePanel'));

        }
        col.find('.ePanel').addClass('eLoaded').click(function (e) {
            cHtml.clearProBox();
            cHtml.options.tempScrollTop = $(window).scrollTop();
            JSBlocks.row = k;
            JSBlocks.col = colK;
            JSBlocks.subrow = subrowK;
            if (JSBlocks.blocks[colV.type].configurable) {
                JSBlocks.getProbox(colV.type);
                cHtml.clearProBox('proBox-' + colV.type);
                cHtml.openProBox(colV.type);
            }
        });

        //If not configurable disable edit button
        if (!JSBlocks.blocks[colV.type].configurable) {
            col.find('.ePanel').addClass('not_config');
        }
        var html = '';
        if (colV.type != "") {
            html += cHtml.getPreviewTitle(colV.type);
        }
        col.find('.ePreview').addClass(JSBlocks.blocks[colV.type].contentClass);
        html += JSBlocks.blocks[colV.type].getPreview(colV.content);//cHtml.getTextHtml(colV.content);
        col.find('.ePreview').html(html);
        if (typeof(title) != 'undefined' && title != false) {
            var prev = col.find('.ePreview');
            title.prependTo(prev);
        }
        return col;

    },
    drawCms: function () {
        var mainContainer = $('#cms-content');
        this.log.info("Drawing cms");
        cHtml.options.tempScrollTop = $(window).scrollTop();
        if (cms.data != null && typeof(cms.data) != 'undefined') {
            if (cms.data.structure != null && typeof(cms.data.structure) != 'undefined' && !$.isEmptyObject(cms.data.structure)) {
                cms.saveLocalStorage();
            }
        }
        mainContainer.html('');
        this.checkEmptyLangs();
        if (cms.data != null) {
            if (typeof(cms.data.structure[JSBlocks.lang]) != 'undefined' && cms.data.structure[JSBlocks.lang] != null) {
                if (cms.data.structure[JSBlocks.lang].length != 0) {
                    $('#copyStructure').html('');
                    $.each(cms.data.structure[JSBlocks.lang], function (k, v) {
                        mainContainer.append(cHtml._renderRow(k, v), cHtml.getGutterRow(k));
                    });
                } else {
                    cHtml.fillCopyStructure();
                }
            } else {
                cHtml.fillCopyStructure();
            }
        }

        //fixme: GUardar en una variable $('#trash') para reutilizarlo despuÃ©s.

        $('#trash').html('');
        $('#trash-container').addClass('hidden');
        if (cms.data != null) {
            if (typeof(cms.data.trash.elements[JSBlocks.lang]) != 'undefined' && cms.data.trash.elements[JSBlocks.lang] != null) {
                if (cms.data.trash.elements[JSBlocks.lang].length > 0) {
                    $('#trash-container').removeClass('hidden');
                    var cont = 0;
                    var row4 = '';
                    $.each(cms.data.trash.elements[JSBlocks.lang], function (k, v) {
                        if (cont == 0) {
                            row4 = $('<div>', {
                                class: 'row eRow'
                            });
                        }
                        var col = cHtml.getCol(3);//v.class-->lo pongo con tamaÃ±o fijo a 3 en vez del tamaÃ±o de la columna
                        var btnDelTrash = $('<span>', {
                            class: 'eTrashBtn'
                        }).html('<i class="fa fa-trash"></i>');
                        btnDelTrash.click(function (e) {
                            e.stopPropagation();
                            col.remove();
                            cms.data.trash.elements[JSBlocks.lang].splice(k, 1);
                            cHtml.drawCms();
                        });
                        col.find('.ePanel').append(btnDelTrash);
                        col.find('.ePanel').addClass('eLoaded').click(function (e) {
                            var row = JSBlocks.row;
                            var col = JSBlocks.col;
                            cms.addFromTrash(row, col, k);
                        });
                        var html = '';

                        if (v.type != 'splitted') {
                            col.find('.ePreview').addClass(JSBlocks.blocks[v.type].contentClass);
                            html = JSBlocks.blocks[v.type].getPreview(v.content);
                        } else {
                            html = '';
                        }
                        col.find('.ePreview').html(html)
                            .append($("<span>", {class: "ePreviewTitle", html: v.type.toUpperCase()}));

                        if (typeof(title) != 'undefined' && title != false) {
                            var prev = col.find('.ePreview');
                            title.prependTo(prev);
                        }

                        col.appendTo(row4);

                        if (cont < 3) {
                            cont++;
                        } else {
                            row4.appendTo($('#trash'));
                            cont = 0;
                        }
                    });

                    if (cont <= 3) {
                        row4.appendTo($('#trash'));
                    }
                    //fixme: Si tenemos que deshabilitar el funcionamiento de la etiqueta A es que no necesitamos esa etiqueta, reemplazarla por span.

                    $('#trash').find('a').each(function (k, v) {
                        $(v).click(function (e) {
                            e.preventDefault();
                        });
                    });
                }
            }
        }

        //if (cms.options.last_copy != JSON.stringify(cms.data)) {
        //    if (cms.options.autosave) {
        //        cp.saveContent();
        //    }
        //    cms.options.hasChanges = true;
        //    cms.options.last_copy = JSON.stringify(cms.data);
        //}
        cHtml.checkChanges();

        $(window).scrollTop(cHtml.options.tempScrollTop);

    },
    checkEmptyLangs: function () {
        $.each(cms.options._langs, function (k, v) {
            if (cms.data != null) {
                if ($.isEmptyObject(cms.data.structure[v.id]) || cms.data.structure[v.id] == null) {
                    $("#lang_" + v.id).addClass("eEmpty");
                } else {
                    $("#lang_" + v.id).removeClass("eEmpty");
                }
            }
        });
    },

    /**
     * Copy structure from one language to another
     */
    fillCopyStructure: function () {
        var container = $('#copyStructure');
        container.html('');
        $.each(cms.data.structure, function (k, v) {
            if (!jQuery.isEmptyObject(v)) {
                $.each(cms.options._langs, function (kl, vl) {
                    if (vl.id == k) {
                        var link = $('<a>', {
                            class: 'btn btn-default',
                            id: 'copy_' + vl.id
                        }).html(copyFrom + ' [' + vl.iso + ']');
                        link.click(function (e) {
                            var toCopy = cms.data.structure[vl.id];
                            cms.data.structure[JSBlocks.lang] = $.extend(true, [], toCopy);
                            container.html('');
                            cHtml.drawCms();
                        });
                        link.appendTo(container);
                    }
                });
            }
        });
    },

    attachDraggable: function (o, rowIndex, colIndex, subrowK) {
        if(subrowK==null || typeof (subrowK)=='undefined') {
            o.draggable({
                revert: "invalid",
                appendTo: "#design",
                helper: "clone",
                start: function (event, ui) {
                    ui.helper.bind("click.prevent",
                        function (event) {
                            event.preventDefault();
                        });
                },
                stop: function (event, ui) {
                    setTimeout(function () {
                        ui.helper.unbind("click.prevent");
                    }, 300);
                }
            });

            o.droppable({
                hoverClass: 'drop-area',
                accept:'.eCol',
                drop: function (event, ui) {
                    console.info("Dropped");
                    var two = ui.draggable.attr("id"),
                        one = $(this).attr("id"),
                        oneCoords = cHtml._getColumnContentFromId(one),
                        twoCoords = cHtml._getColumnContentFromId(two),
                        oneObj=$.extend(true, {}, cms.data.structure[JSBlocks.lang][oneCoords.row].content[oneCoords.column]),
                        twoObj=$.extend(true, {}, cms.data.structure[JSBlocks.lang][twoCoords.row].content[twoCoords.column]),
                        oneClass=parseInt(oneObj.class),
                        twoClass=parseInt(twoObj.class);


                    oneObj.class=twoClass;
                    twoObj.class=oneClass;
                    cms.data.structure[JSBlocks.lang][twoCoords.row].content[twoCoords.column]=oneObj;
                    cms.data.structure[JSBlocks.lang][oneCoords.row].content[oneCoords.column]=twoObj;
                    cHtml.drawCms();
                    setTimeout(function(){
                        $(two).addClass('dropped')
                    },600)

                }
            });
            this.log.info("Attached drag action");
        }
    },
    _getColumnContentFromId: function (id) {
        var pieces = id.match(/\_([\d]{1,2})-([\d]{1,2})/);

        return {
            row : pieces[1],
            column : pieces[2]
            };


    },
    /**
     * Attach handler and resizable properties to column
     * @param o
     * @param rowIndex
     * @param colIndex
     * @param subrowK
     */
    attachDragCol: function (o, rowIndex, colIndex, subrowK) {
        if (typeof subrowK == "undefined") {
            subrowK = null;
        }
        var id = "eCol_" + rowIndex + "-" + colIndex + (subrowK != null ? subrowK : '');


        o.attr("id", id);
        if (subrowK == null) {
            var stepSize = $("#cms-content").width() / 12,
                minSize = 3,
                handler = o.find(".eHandlerCol");

            o.resizable({
                grid: [stepSize, 10000000],
                minWidth: stepSize * 2,
                alsoResizeReverse: "#eCol_" + rowIndex + "-" + (colIndex + 1),
                handles: {'e,w': handler},
                resize: function (e, ui) {
                    var difference = ui.originalSize.width - ui.size.width;
                    if (Math.abs(difference) >= stepSize) {
                        var steps = Math.abs(Math.round(difference / stepSize));
                        if (difference < 0) {
                            if ((cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex + 1].class - (steps + 1)) >= minSize) {

                            } else {
                                o.resizable("option", "maxWidth", ui.size.width);
                                return;
                            }
                        } else {
                            if ((cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex].class - steps) >= minSize) {

                            } else {
                                o.resizable("option", "minWidth", ui.size.width);
                                return;
                            }
                        }
                    }
                },
                stop: function (e, ui) {
                    var difference = ui.originalSize.width - ui.size.width;
                    if (Math.abs(difference) >= stepSize) {
                        var steps = Math.abs(Math.round(difference / stepSize));
                        if (difference < 0) {
                            if ((cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex + 1].class - steps) >= minSize) {
                                cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex].class += steps;
                                cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex + 1].class -= steps;
                            }
                        } else {
                            if ((cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex].class - steps) >= minSize) {
                                cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex].class -= steps;
                                cms.data.structure[JSBlocks.lang][rowIndex].content[colIndex + 1].class += steps;
                            }
                        }
                    }
                    cHtml.drawCms();
                }
            });
        }
    }
};
