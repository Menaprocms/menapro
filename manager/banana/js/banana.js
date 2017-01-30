/**
 * Created by XNX-PTL on 08/08/2015.
 * todo: Añadir funcionalidad swipe
 * todo: Tecla escape cuando estamos en preview
 * todo: Stop previous ajax file info request
 * todo: Rename folder, añadir botón y funcionalidad
 * todo: Cuando estamos en el modo multiple hay que evitar el getpreview en la búsqueda
 */

(function ($) {

    $.fn.banana = function (options) {

        // This is the easiest way to have default options.
        var settings = $.extend(true, {

            url: "",                    //Url to retrieve data
            urlUpload: "",              //Url to upload script
            level: [0],                 //Default depth level, check doc.
            folder: "",                 //Default folder to open
            target: "",                 //(id|class|jQuery|function) Where to set selected value
            tagTarget: "",              //Images only, target for automated SRC tag created with the url of image selected
            tagTargetClass: "",         //Additional classes for the Src tag created
            thumbsFolder: "thumbs/",    //Thumbs folder
            resetFolderOnStart: true,   //If false, the last folder opened will remain opened
            allowDelete: true,          //Enable or disable delete buttons
            confirmDeletion: false,     //If true a confirmation dialog will appear before delete file
            multipleUpload: true,       //Allow multiple selection when uploading a file
            allowUpload: true,          //Show or hide upload box.
            allowFolderCreate: true,    //Show or hide create folder icon and form
            multiple: false,            //Wether use multiple selection or single,
            selectedColor: false,       //Color to use for selected elements.
            preview: true,              //Enable preview screen when file is selected
            fileTypes: false,           //View only certain datatypes. Use an array with extensions (For images use ["image"]). Remember that this will only filter view, not load. fileTypes is sended to server too, so filter in server also.
            preloadPreview: true,       // Preload the next and previous image to make transition smooth
            hideEmptyFolders: false,    // Only for tree view. Hides all folders that haven´t files (Or have not any of extensions filtered),
            imageLabels: true,          // Show a label with filename near the thumb. Only on hover.

            token: "",                  // If your app needs to send a token set here,
            tokenName: "token",         // Name to use when the token is sended

            iconsMode: false,            // Enable icon mode. This disables other configuration except [multiple, preview].
            iconsData: [],               // JSON file with icons info. Array or url to JSON
            iconsPrefix: "fa fa-",       // Prefix of the fontFamily. Defaults to FontAwesome by Dave Gandy


            i18n: {                     // The name of the properties returned by fileinfo can be translated too. Add a key with same text of your prop.
                treeTitle: "Folders tree",
                newFolder: "New folder",
                clickToReload: "Click here to reload",
                search: "Search",
                urlEmpty: "Incomplete configuration, empty url.",
                iconsLoadError: "Error while loading icons data"

            }
        }, options);

        var banana = {
            _settings: {},
            __settings: {
                action: "list",
                item: "",
                uploadDir: "",
                filesDb: new Array,
                resultsContainer: "",
                imageExtensions: ["bmp", "jpg", "jpeg", "gif", "png"],
                selectedItems: [],
                tempScroll: false,
                iconsDataBuffer: [],
                list: []
            },

            _init: function (settings) {

                $("body").css("position", "relative");
                this._settings = settings;
                this._resetSelectedItems();
                this._appendContainer();
                this._attachScroll();
                this._lockDragOnDocument();
                if (settings.url.trim() != "" || settings.iconsMode) {
                    //this._getData();
                } else {
                    this._displayError(this._settings.i18n.urlEmpty)
                }


                banana._triggerOutClick();
            }, _resetSelectedItems: function () {
                this.__settings.selectedItems = [];
            },
            _attachWindowResize: function (o) {
                $(window).resize(function (e) {
                    banana._move(o);

                })

                $(window).scroll(function () {
                    clearTimeout($.data(this, 'scrollTimer'));
                    $.data(this, 'scrollTimer', setTimeout(function () {
                        banana._move(o)
                    }, 200));
                });
            },
            _attachScroll: function () {
                var cont = banana._settings.container;
                this._settings.container.scroll(function () {
                    if (cont.scrollTop() > 30) {
                        cont.addClass("bnscrolled");
                    } else {
                        cont.removeClass("bnscrolled");
                    }
                });
                //$("#banana .bananaContainer").scrollTop()
                //cont.scrollTop();
            },
            _createContainer: function () {
                var intCont = $("<div>", {class: "bananaContainer bananimate "});
                var extCont = $("<div>", {
                    id: "banana",
                    class: "bananimate bye", //replace with param
                    html: intCont
                });
                $.extend(banana._settings, {container: intCont});

                intCont.bind('mousewheel DOMMouseScroll', function (e) {
                        var scrollTo = null;

                        if (e.type == 'mousewheel') {
                            scrollTo = (e.originalEvent.wheelDelta * -1);
                        }
                        else if (e.type == 'DOMMouseScroll') {
                            scrollTo = 40 * e.originalEvent.detail;
                        }

                        if (scrollTo) {
                            e.preventDefault();
                            $(this).scrollTop(scrollTo + $(this).scrollTop());
                            // Comment the line before and uncomment the next to animate the scrolling in banana container
                            //$(this).stop().animate({scrollTop:scrollTo + $(this).scrollTop()}, '500', 'swing')
                        }
                    }
                );
                //Drag and drop
                var contCounter = 0;
                intCont.on('dragenter', function (e) {
                    if (banana._settings.allowUpload) {
                        e.stopPropagation();
                        e.preventDefault();
                        contCounter++;
                        $(this).addClass('dragEnter');
                    }

                }).on('dragover', function (e) {
                    if (banana._settings.allowUpload) {
                        e.stopPropagation();
                        e.preventDefault();
                    }
                }).on('dragleave', function (e) {
                    if (banana._settings.allowUpload) {
                        e.stopPropagation();
                        e.preventDefault();
                        contCounter--;
                        if (contCounter == 0) {
                            $(this).removeClass('dragEnter');
                        }
                    }
                }).on('drop', function (e) {
                    if (banana._settings.allowUpload) {
                        $(this).removeClass('dragEnter');

                        e.preventDefault();
                        var files = e.originalEvent.dataTransfer.files;

                        //We need to send dropped files to Server
                        banana._dropFileUpload(files);
                    }
                });

                return extCont;

            },
            _appendContainer: function () {
//                $("body").append(banana._createContainer());
                if (!document.getElementById("banana")) {

                    $("body").append(banana._createContainer());
                } else {
                    banana._settings['container'] = $("#banana .bananaContainer ");
                    /*   $.extend(banana._settings, {
                     container: $("#banana")
                     });*/
                }
            },
            _getLoader: function (small) {
                var loader = $("<div>", {
                    class: "la-ball-clip-rotate-multiple la-dark " + (small ? "" : "la-3x"),
                    style: "display:none"
                });
                loader.append($("<div>")).append($("<div>"));
                loader.delay(250).fadeIn(200);
                return loader;
            },
            _getData: function (animation) {
                if (typeof animation == "undefined") {
                    animation = true;
                }

                banana._resetContainer(true);

                if (banana._settings.iconsMode) {
                    banana._getIconsData();
                    return;
                }

                banana.__settings.list = [];
                //var tempScroll = banana._settings.container.scrollTop();
                var dataToSend={
                    action: banana.__settings.action,
                    item: banana.__settings.item,
                    folder: banana._settings.folder,
                    fileTypes: banana._settings.fileTypes
                };
                dataToSend[banana._settings.tokenName]=banana._settings.token;
                $.ajax({
                    type: "POST",
                    url: settings.url,
                    dataType: "JSON",
                    data: dataToSend,
                    beforeSend: function () {
                        if (banana._settings.fileTypes) {
                            banana._settings.fileTypes = banana._settings.fileTypes.map(function (word) {
                                return word.toLowerCase()
                            })
                        }
                        tempScroll = banana._settings.container.scrollTop();
                        if (animation) {
                            banana._settings.container.html("").append(banana._getLoader(false))
                        }
                    },
                    success: function (data, textStatus, jqXHR) {

                        if (data.error) {
                            banana._displayError(data.error);
                        } else {
                            banana._parseData(data, animation);
                        }
                        banana._settings.container.scrollTop(tempScroll);

                        banana.__settings.action = "list";
                        banana.__settings.item = "";
                    }
                }).fail(function (t) {
                    banana._displayError("Error while loading data.");

                    banana._resetData();
                })

            }, _resetData: function (all) {


                if (banana._settings.multiple && all) {
                    banana.__settings.selectedItems = [];
                }
                banana.__settings.action = "list";
                banana.__settings.item = "";
                if (all) {
                    banana._settings.folder = "";
                }
            },
            _displayError: function (msg) {
                this._settings.container.empty();
                this._settings.container.append(
                    $("<div>", {class: "error", html: "[ERR] " + msg})
                );
                this._settings.container.append(
                    $("<p>", {
                        html: this._settings.i18n.clickToReload
                    }).click(function (e) {
                        e.stopPropagation();
                        banana._resetData(true);
                        banana._getData();
                    })
                )
            },
            _appendSearch: function () {
                return $("<div>", {
                    class: "searchDiv",
                    html: $("<input>", {
                        type: "text",
                        class: "searchInput",
                        placeholder: banana._settings.i18n.search
                    }).keyup(function () {
                        var value = $(this).val();

                        if (banana._settings.iconsMode) {
                            if (value.length <= 0) {
                                banana._parseIcons(banana.__settings.iconsDataBuffer)
                            } else {
                                banana._parseIcons(banana._searchIcons(value))
                            }
                        } else {
                            if (value.length >= 3) {
                                banana._search(value, banana.__settings.filesDb)
                            } else if (value.trim() == "") {
                                banana._getTreeData();
                            } else {
                                banana.__settings.resultsContainer.html("");

                            }
                        }
                    })

                });
            },
            _processTree: function (data) {
                var cont = banana._settings.container;
                cont.empty();
                cont.append(banana._getTopBar(banana._settings.i18n.treeTitle, true));
                var results = $("<div>", {class: "bnresults"});
                banana.__settings.resultsContainer = results;
                var searchForm = banana._appendSearch();
                cont.append(searchForm);
                $(searchForm).find('input')[0].focus();
                var ul = $("<ul>", {
                    class: "bananaTree"
                });


                banana.__settings.filesDb = [];
                banana._treeLi(data.data, ul, 0);

                results.append(ul);
                cont.append(results);


            }, _toDb: function (data) {
                if (typeof data == "object") {

                    if (banana._areImagesFiltered()) {
                        if (typeof data.pics == "object") {
                            $.each(data.pics, function (kp, vp) {
                                banana.__settings.filesDb.push(vp);
                            });
                        }

                    }
                    //todo: make folders appear in results too
                    if (typeof data.folders == "object") {
                        $.each(data.folders, function (kf, vf) {
                            banana.__settings.filesDb.push(vf);
                        });
                    }


                    if (typeof data.others == "object") {
                        $.each(data.others, function (k, v) {
                            if (banana._settings.fileTypes && banana._settings.fileTypes.indexOf(k) < 0) {
                                return true;
                            }
                            if (typeof v == "object") {
                                $.each(v, function (kb, vb) {

                                    banana.__settings.filesDb.push(vb);
                                });

                            }
                        });

                    }

                }
            }, _search: function (keyword, where) {

                var results = where.filter(function (o) {
                    if (o.fullDir.toLowerCase().indexOf(keyword.toLowerCase()) >= 0) {
                        return true;
                    }
                    //return o.fileName.indexOf(keyword);
                });
                banana._processResults(results);
            }, _processResults: function (results) {
                var ul = $("<ul>", {class: "searchResults"});

                $.each(results, function (k, v) {

                    var li = $("<li>", {
                        html: v.fileName,
                        title: v.fullDir
                    }).click(function (e) {
                        e.stopPropagation();
                        if (banana._settings.preview) {
                            banana._getPreviewScreen(v);
                        } else {
                            banana._select(v.fullDir);
                        }

                    });

                    var splittedUrl = v.fileName.split(".");
                    if (banana.__settings.imageExtensions.indexOf(splittedUrl[splittedUrl.length - 1].toLowerCase()) >= 0) {
                        li.prepend($("<span>", {
                            class: "searchPreview",
                            style: "background-image:url('" + banana._settings.thumbsFolder + v.fullDir.replace("../","") + "')"
                        }))
                    }


                    ul.append(li)
                });

                banana.__settings.resultsContainer.html(ul);

            },
            _treeLi: function (row, cont, depth) {


                $.each(row, function (k, v) {
                    depth++;


                    var li = $("<li>", {
                        text: v.name,
                        title: v.fullDir,
                        class: (v.depth > 1 ? "" : "root"),
                        style: "padding-left:" + (depth * 15) + "px"
                    }).click(function (e) {
                        e.stopPropagation();
                        banana._settings.folder = v.fullDir;

                        banana._getData(true);
                    });
                    li.append($("<span>", {
                        html: v.filesCount > 0 ? v.filesCount : "",
                        class: "filesNumber"
                    }));

                    banana._toDb(v.files);

                    if (banana._settings.hideEmptyFolders && v.filesCount <= 0) {

                    } else {
                        cont.append(li);
                    }
                    if (!$.isEmptyObject(v.subfolders)) {

                        banana._treeLi(v.subfolders, cont, parseInt(depth));
                    }
                    depth--;
                });

            }, _getTreeData: function () {
                var dataTosend = {
                    action: "tree",
                    fileTypes: banana._settings.fileTypes
                };
                dataTosend[banana._settings.tokenName] = banana._settings.token
                $.ajax({
                    url: banana._settings.url,
                    type: "POST",
                    data: dataTosend,
                    dataType: "JSON",
                    beforeSend: function () {
                        $(banana._settings.container).html(banana._getLoader(false));
                    },
                    success: function (data) {


                        if (data.error.length > 0) {
                            banana._displayError(data.error);
                        } else {
                            banana._processTree(data);
                        }

                    }
                }).fail(function (data) {

                    banana._displayError("Error retrieving tree");
                    if (console) {
                        console.log(data)
                    }
                    ;
                });
            }, search: function (data, key) {

                for (var property in data) {
                    if (data.hasOwnProperty(property)) {
                        if (property == key) {
                            delete data[key];
                        }

                        else {
                            if (typeof data[property] === "object") {
                                deleteRecursive(data[property], key);
                            }
                        }
                    }
                }
            },
            _getTopBar: function (currentFolder, isTree) {
                var bnn = this;
                var backButton = $("<span>", {class: "bnIco back"}).click(function (e) {
                    e.stopPropagation();

                    if (banana._settings.folder.trim() != "") {

                        bnn._settings.folder = banana._settings.folder.substring(0, banana._settings.folder.lastIndexOf("/"))
                    }

                    banana._getData();

                });

                var treeButton = $("<span>", {class: "bnIco tree"}).click(function (e) {
                    e.stopPropagation();

                    banana._getTreeData();
                });

                var closeButton = $("<span>", {class: "bnIco bnclose"}).click(function (e) {
                    e.stopPropagation();
                    banana._hideBanana();
                });
                var newFolderButton = $("<span>", {class: "bnIco bnNewFolder"}).click(function (e) {
                    e.stopPropagation();
                    banana._settings.container.removeClass("bneditmode");
                    $(this).hide();
                    if ($(banana._settings.container).find(".newFolderForm").length <= 0) {
                        var form = banana._getFolderForm();
                        banana._settings.container.prepend(form);
                        form.find('input').focus();
                    }

                });
                var editButton = $("<span>", {class: "bnIco edit bananimate"}).click(function (e) {
                    e.stopPropagation();
                    banana._settings.container.toggleClass("bneditmode");

                });
                var title = $("<span>", {id: "currentFolder", html: currentFolder});
                var confirmSelection = $("<span>", {class: "bnIco bnConfirmSelection bananimate"}).click(function (e) {
                    e.stopPropagation();

                    banana._select(banana.__settings.selectedItems);

                });

                return $("<div>", {
                    class: "menu bananimate",
                    html: (((banana._settings.folder.replace("../", "").indexOf("/") >= 0 && banana._settings.folder.replace("../", "").trim() != "") || isTree) ? backButton : treeButton)
                }).append(
                    [banana._settings.folder.trim() != "" ? title : "",
                        banana._settings.multiple && !isTree ? confirmSelection : "",
                        closeButton,
                        isTree ? "" : editButton,
                        isTree || !banana._settings.allowFolderCreate ? "" : newFolderButton,
                    ]);

            },
            _hideBanana: function () {
                var mcont = $("#banana");
                mcont.addClass('bye');
                setTimeout(function () {
                    if (banana._settings.multiple) {
                        banana.__settings.selectedItems = [];
                    }
                    banana._resetContainer(true);
                    mcont.hide();
                    $('body').removeClass('bananaOpened');
                }, 300);

            },
            _getFolderForm: function () {
                var inputText = $("<input>", {
                    type: "text",
                    placeholder: this._settings.i18n.newFolder
                }).click(function (e) {
                    e.stopPropagation()
                }).keyup(function (event) {
                    var key = event.keyCode || event.which;

                    if (key === 13) {
                        banana._createFolder($(this).val())
                    }

                    return false;
                });
                var cont = $("<div>", {
                    class: "newFolderForm"

                }).append(
                    inputText
                ).append(
                    $("<button>", {html: "OK"}).click(function (e) {
                        e.preventDefault();
                        banana._createFolder(inputText.val());
                    })
                );

                return cont;
            },
            _createFolder: function (folderName) {
                banana.__settings.item = folderName;
                banana.__settings.action = "makedir";
                banana._getData(false);
            },
            _parseData: function (data, animation) {
                var cont = banana._settings.container;
                cont.empty();
                cont.append(banana._getTopBar(data.currentFolder, false));
                //banana.__settings.uploadDir = data.currentFolderFull;


                var ulFCont = $("<ul>", {class: "folders"});

                if (data.folders.length) {


                    $.each(data.folders, function (k, v) {
                        ulFCont.append(
                            $("<li>", {
                                html: v.dirName,
                                class: "bananimate"
                            }).click(function (e) {
                                e.stopPropagation();
                                if (banana._settings.multiple) {
                                    banana.__settings.selectedItems = [];
                                }
                                banana._resetData();
                                banana._settings.folder = v.fullDir;
                                banana._getData();
                            }).append(
                                (banana._settings.allowDelete && v.isEmpty) ? $("<div>", {
                                    class: "bnIco bndeleteFolder bananimate"
                                }).click(function (e) {
                                    e.stopPropagation();

                                    banana.__settings.action = "delete";
                                    banana.__settings.item = v.fullDir;

                                    if (banana._settings.confirmDeletion) {
                                        if (confirm("Delete selected item")) {
                                            banana._getData(false);
                                        } else {

                                            banana._resetData();
                                        }
                                    } else {
                                        banana._getData(false);
                                    }


                                }) : ""
                            )
                        )
                    });


                }


                cont.append(ulFCont);
                var fileUploadInput = $("<input>", {type: "file", class: "bananaUpload"}).change(function () {

                    banana._fileUpload(this);
                });


                if (banana._settings.multipleUpload) {
                    fileUploadInput.attr("multiple", "multiple");
                }
                var uploadPicturesLi = $("<li>", {
                    class: "upload  bananimate" + (animation ? "minimized" : ""),
                    id: "bananaUploadContainer",
                    //html: $("<span>", {class: "bananimate ", html: "+"})
                    html: fileUploadInput
                }).append(
                    $("<span>", {class: "bananimate bnIco ", html: ""})
                );

                var ulCont = $("<ul>", {class: "pictures"}),
                    listIndex = 0;

                if (banana._settings.allowUpload) {
                    ulCont.append(uploadPicturesLi);
                }

                if (banana._areImagesFiltered()) {


                    if (data.pics.length) {


                        $.each(data.pics, function (k, v) {
                            var listKey = parseInt(banana.__settings.list.push(v)) - 1;

                            ulCont.append(
                                $("<li>", {
                                    html: [banana._settings.allowDelete ? $("<div>", {
                                        class: "bnIco bndeleteImg bananimate"
                                    }).click(function (e) {
                                        e.stopPropagation();
                                        banana.__settings.action = "delete";
                                        banana.__settings.item = v.fullDir;


                                        if (banana._settings.confirmDeletion) {
                                            var imgobj = $(this).parent();
                                            if (confirm("Delete selected item")) {

                                                imgobj.addClass("deleted");
                                                setTimeout(function () {
                                                    banana._delMultipleItem(v);
                                                    banana._getData(false);

                                                }, 350);

                                            } else {
                                                banana._resetData();
                                            }

                                        } else {
                                            $(this).parent().addClass("deleted");
                                            setTimeout(function () {
                                                banana._delMultipleItem(v);
                                                banana._getData(false);
                                            }, 350)
                                        }


                                    }) : "",
                                        banana._settings.imageLabels ? $("<span>", {
                                            class: "bn_label",
                                            html: v.fileName
                                        }) : ""
                                    ],
                                    style: "background-image:url('" + banana._settings.thumbsFolder + v.fullDir.replace("../","") + "')",
                                    class: "bananimate " + (animation ? "minimized" : "") + " " + v.fileType,
                                    title: v.fileName


                                }).click(function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    var o = $(e.currentTarget);
                                    if (banana._settings.multiple) {
                                        if (banana._selectMultiple(v)) {
                                            o.addClass("selected");
                                            if (banana._settings.selectedColor) {
                                                o.css('background-color', banana._settings.selectedColor)
                                            }
                                        } else {
                                            if (banana._settings.selectedColor) {
                                                o.css('background-color', "")
                                            }
                                            o.removeClass("selected");
                                        }
                                    } else {
                                        if (banana._settings.preview) {
                                            banana._getPreviewScreen(v, listKey);
                                        } else {
                                            banana._select(v.fullDir);
                                        }
                                    }

                                })
                            )
                        });


                    }


                }
                cont.append(ulCont);
                //Other formats


                if (typeof data.others != "undefined") {
                    cont.append($("<div>", {class: "clearfix"}));
                    $.each(data.others, function (k, v) {

                        if (banana._settings.fileTypes && banana._settings.fileTypes.indexOf(k) < 0) {
                            return true;
                        }

                        var otherTitle = $("<div>", {
                            html: k,
                            class: "otherTitles"
                        });
                        var ulOCont = $("<ul>", {class: "other"});

                        $.each(v, function (kl, vl) {
                            var listKey = parseInt(banana.__settings.list.push(vl)) - 1;


                            var otherLi = $("<li>", {
                                class: vl.fileType + " " + k,
                                html: $("<span>", {html: vl.fileName.replace(/\|\-|\_/gi, " "), class: "otherName"}),
                                title: vl.fileName
                            }).click(function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                var o = $(e.currentTarget);
                                if (banana._settings.multiple) {
                                    if (banana._selectMultiple(vl)) {
                                        o.addClass("selected");
                                        if (banana._settings.selectedColor) {
                                            o.css('color', banana._settings.selectedColor)
                                        }
                                    } else {
                                        o.removeClass("selected");
                                        o.css('color', "");

                                    }
                                } else {
                                    if (banana._settings.preview) {
                                        banana._getPreviewScreen(vl, listKey);
                                    } else {
                                        banana._select(v.fullDir);
                                    }

                                }

                            });

                            otherLi.append((banana._settings.allowDelete) ? $("<div>", {
                                    class: "bnIco bndeleteFolder bananimate"
                                }).click(function (e) {
                                    e.stopPropagation();

                                    banana.__settings.action = "delete";
                                    banana.__settings.item = vl.fullDir;

                                    if (banana._settings.confirmDeletion) {
                                        if (confirm("Delete selected item")) {
                                            banana._delMultipleItem(vl);
                                            banana._getData(false);
                                        } else {

                                            banana._resetData();
                                        }
                                    } else {
                                        banana._delMultipleItem(vl);
                                        banana._getData(false);
                                    }


                                }) : ""
                            );

                            ulOCont.append(otherLi)
                        });


                        cont.append(otherTitle);
                        cont.append(ulOCont);
                    });
                }


                //ENd other formats

                cont.append($("<div>", {class: "overflowShadow"}));
                //cont.append($("<div>",{class:"overflowShadow top"}))
                lapse = animation ? 30 : 0;
                if (banana.__settings.tempScroll) {
                    banana._settings.container.animate({scrollTop: banana.__settings.tempScroll}, 150);
                    banana.__settings.tempScroll = false
                }


                cont.find("ul.pictures li").each(function (k, v) {
                    setTimeout(function () {
                        $(v).removeClass("minimized")
                    }, (k + 1) * lapse);

                });

            },
            _delMultipleItem: function (file) {
                this.__settings.selectedItems = this.__settings.selectedItems.filter(function (row) {
                    return row.fullDir != file.fullDir;
                });

                var cfbtn = $(".bnConfirmSelection");
                if (this.__settings.selectedItems.length <= 0) {
                    cfbtn.removeClass("on");
                }

            },
            _addMultipleItem: function (file) {
                this.__settings.selectedItems.push(file);
                var cfbtn = $(".bnConfirmSelection");

                cfbtn.addClass("on");


            },
            _selectMultiple: function (file) {

                var exist = this.__settings.selectedItems.filter(function (row) {
                    return row.fullDir == file.fullDir;
                });
                if (exist.length > 0) {
                    this._delMultipleItem(file);

                    return false;
                } else {
                    this._addMultipleItem(file);
                    return true;
                }


            },
            _move: function (o) {

                if (banana._settings.multiple) {
                    banana._settings.container.addClass("multiple")
                } else {
                    banana._settings.container.removeClass("multiple")

                }
                if (window.innerWidth <= 500) {
                    $('body').addClass('bananaOpened');
                    banana._settings.container.parent().css('height', $(window).height());

                } else {
                    banana._settings.container.parent().css('height', "")

                }
                var left = 0,
                    top = 0,
                    coords = $(o).offset(),
                    cont = banana._settings.container.parent(),
                    ob = $(o);

                left = coords.left + ob.width() + (parseInt(ob.css("padding-left")) * 2);
                if (left + cont.width() >= $(window).width()) {
                    left = coords.left - cont.width();
                }

                var topCoord = coords.top - $(document).scrollTop();
                top = topCoord - cont.height() / 2 + ob.height() + parseInt(ob.css("padding-top"));
                if (top <= 0) {
                    top = topCoord + ob.height() / 2 + parseInt(ob.css("padding-top"));
                }
                cont.css({
                    left: left + "px",
                    top: top + "px"

                });


            },

            _select: function (selected) {


                var target = banana._settings.target;
                if (typeof(target) == "function") {

                    target(selected);
                } else if (target instanceof jQuery) {

                    target.val(selected)
                } else if (typeof target == "string" && target.trim().length) {

                    $(target).val(selected)
                }

                if (!banana._settings.multiple && !banana._settings.iconsMode) {
                    var tagTarget = banana._settings.tagTarget;
                    var toAppend;
                    if (tagTarget instanceof jQuery) {

                        if (toAppend = banana._createSrc(selected)) {
                            tagTarget.append()
                        }
                    } else if (typeof(tagTarget) == "string" && tagTarget.trim().length) {

                        if (toAppend = banana._createSrc(selected)) {
                            $(tagTarget).html(banana._createSrc(selected))
                        }
                    }
                }


                banana._hideBanana();
            },
            _createSrc: function (url) {

                var splittedUrl = url.split(".");
                if (banana.__settings.imageExtensions.indexOf(splittedUrl[splittedUrl.length - 1].toLowerCase()) >= 0) {
                    return $("<img>", {
                        src: url,
                        class: banana._settings.tagTargetClass
                    });
                } else {

                    return false
                }

            }, _areImagesFiltered: function () {
                var result = false;

                if (!banana._settings.fileTypes || banana._settings.fileTypes.indexOf('image') >= 0) {
                    return true;
                }
                $.each(banana.__settings.imageExtensions, function (k, v) {
                    if (banana._settings.fileTypes.indexOf(v) >= 0) {
                        result = true;
                        return false;
                    }

                });
                return result


            },
            _lockDragOnDocument: function () {
                $(document).on('dragenter', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
                $(document).on('dragover', function (e) {
                    e.stopPropagation();
                    e.preventDefault();

                });
                $(document).on('drop', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                });
            },
            _triggerOutClick: function () {
                var cont = $("#banana");

                $(document).click(function (event) {
                    if (cont.is(":visible")) {
                        if (!$(event.target).closest("#banana").length) {


                            banana._hideBanana();
                            //cont.hide()


                        }
                    } else {
                        return true
                    }
                })
            },
            _dropFileUpload: function (files) {
                var data = new FormData();
                jQuery.each(files, function (i, file) {
                    data.append('file[]', file);

                });

                banana._ajaxFiles(data);
            },
            _fileUpload: function (input) {


                jQuery.each(jQuery(input)[0].files, function (i, file) {
                    var data = new FormData();
                    data.append('file[]', file);
                    banana._ajaxFiles(data);

                });


            }, _ajaxFiles: function (data) {

                var hidden = false;
                if (banana._settings.fileTypes) {
                    $.each(banana._settings.fileTypes, function (k, v) {
                        data.append('fileTypes[]', v);

                    });
                }

                data.append('action', "upload");
                data.append(banana._settings.tokenName, banana._settings.token);

                data.append('folder', banana._settings.folder);
                jQuery.ajax({
                    url: banana._settings.urlUpload,
                    data: data,
                    cache: false,
                    async: true,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    beforeSend: function () {

                        $("#bananaUploadContainer").html(
                            banana._getLoader(true)
                        ).append(
                            $("<span>", {
                                id: "bananaProgress"
                            })
                        );

                    },
                    success: function (responseData) {

                        console.warn(responseData);
                        banana._getData(false);

                    }, xhr: function () {
                        var bProgress = $("#bananaProgress"), hidden = false;
                        var xhrobj = $.ajaxSettings.xhr();
                        if (xhrobj.upload) {
                            xhrobj.upload.addEventListener('progress', function (event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                    if (percent >= 95 && !hidden) {
                                        hidden = true;

                                        bProgress.hide();


                                    } else if (percent < 95) {
                                        bProgress.css({
                                            width: percent + "%"

                                        });
                                    }
                                }
                                //Set progress


                            }, false);
                        }
                        return xhrobj;
                    }
                }).fail(function (data) {
                    console.error(data)

                });
            }, _resetContainer: function (resetScroll) {
                if (resetScroll) {
                    banana.__settings.tempScroll = false;
                    banana._settings.container.scrollTop(0);
                }
                banana._settings.container.removeClass('bnpreview').css("background-image", "");
                $(document).unbind('keydown');
            }, _bindArrowKeys: function (key) {
                var list = banana.__settings.list;
                if (list[key - 1] || list[key + 1]) {


                    $(document).unbind('keydown').bind('keydown', function (e) {
                        switch (e.which) {
                            case 37: // left
                                if (list[key - 1]) {

                                    banana._getPreviewScreen(list[key - 1], key - 1);
                                } else {
                                }
                                break;

                            case 38: // up
                            case 40: //down
                                banana._resetContainer();
                                banana._getData(false);
                                break;

                            case 39: // right
                                if (list[key + 1]) {
                                    banana._getPreviewScreen(list[key + 1], key + 1);
                                } else {
                                }
                                break;


                            default:
                                return; // exit this handler for other keys
                        }

                        e.preventDefault();

                    });
                }
            }, _getPreviewScreen: function (file, listIndex) {
                banana._resetContainer(false);

                if (listIndex >= 0) {
                    banana._bindArrowKeys(listIndex);
                }

                banana.__settings.tempScroll = banana._settings.container.scrollTop();
                bg = false;

                var ext = file.fullDir.substring(file.fullDir.lastIndexOf(".") + 1).toLowerCase();

                if (banana.__settings.imageExtensions.indexOf(ext) >= 0) {
                    bg = true;
                }

                //Preload next image:
                if (banana._settings.preloadPreview) {
                    if (banana.__settings.list[listIndex + 1]) {
                        if (banana.__settings.imageExtensions.indexOf(banana.__settings.list[listIndex + 1].fullDir.substring(banana.__settings.list[listIndex + 1].fullDir.lastIndexOf(".") + 1).toLowerCase()) >= 0) {
                            $('<img/>')[0].src = banana.__settings.list[listIndex + 1].fullDir;
                        }
                    }
                    if (banana.__settings.list[listIndex - 1]) {
                        if (banana.__settings.imageExtensions.indexOf(banana.__settings.list[listIndex - 1].fullDir.substring(banana.__settings.list[listIndex - 1].fullDir.lastIndexOf(".") + 1).toLowerCase()) >= 0) {
                            $('<img/>')[0].src = banana.__settings.list[listIndex - 1].fullDir;
                        }
                    }
                }


                var src = bg ? $("<img>", {
                        //style: "background-image:url('" + (bg ? file.fullDir : "") + "')",
                        class: "bn_previewImage",
                        src: (bg ? file.fullDir : "")


                    }) : $("<div>", {
                        class: 'bn_previewFile',
                        html: [
                            $("<div>", {
                                class: 'extension ' + ext,
                                html: ext
                            }),
                            file.fileName]
                    }),
                    okbtn = $("<span>", {
                        class: 'bnIco bn_btnok',
                        click: function () {
                            banana._select(file.fullDir);
                        }
                    }),
                    kobtn = $("<span>", {
                        class: 'bnIco bn_btnko',
                        click: function () {
                            banana._resetContainer();
                            banana._getData(false);

                        }
                    }),
                    btnscnt = $("<div>", {
                        class: 'bn_btn_cont',
                        html: [okbtn, kobtn]
                    });


                var prwCnt = $("<div>", {
                    id: "bn_previewContainer",
                    html: [].concat($("<div>", {
                        class: "bn_previewwrapper",
                        html: src
                    }), btnscnt, $("<div>", {class: 'bn_properties'}))


                });


                banana._settings.container.html(prwCnt).addClass('bnpreview');
                if (bg) {
                    banana._settings.container.css({
                        "background-image": "url('" + file.fullDir + "')"

                    })
                }

                this._getFileInfo(file);
                banana._settings.container.scrollTop(0);


            }, _getFileInfo: function (file) {
                //    Ajax returning values
                var datas = [],
                    dest = $(".bn_properties"),
                    loader = banana._getLoader(),
                    dataToSend = {
                        action: 'fileinfo',
                        file: file.fullDir,
                        fileTypes: banana._settings.fileTypes
                    };
                    dataToSend[banana._settings.tokenName]=banana._settings.token;
                $.ajax({
                    type: "POST",
                    url: settings.url,
                    dataType: "JSON",
                    data: dataToSend,
                    beforeSend: function () {
                        //dest.html(loader)
                    },
                    success: function (data, textStatus, jqXHR) {

                        loader.hide();
                        if (data.error) {
                            banana._displayError(data.error);
                        } else {

                            $.each(data, function (k, v) {
                                datas.push(
                                    $("<div>", {
                                        class: "bn_property",
                                        html: [
                                            $("<span>", {
                                                class: 'bn_prop_title',
                                                html: banana._settings.i18n[k] ? banana._settings.i18n[k] : k
                                            }),
                                            $("<span>", {
                                                class: 'bn_prop_value',
                                                html: v
                                            })

                                        ]
                                    })
                                );

                            });
                            dest.append(datas);
                        }

                    }
                }).fail(function (t) {
                    banana._displayError("Error while loading data.");

                    banana._resetData();
                });


                //var sample = {
                //    name: "a file name",
                //    fileSize: "20kb",
                //    uploaded: "2012-15-00",
                //    dimensions: "2000x1500px"
                //
                //};


            },
            _getIconsData: function () {
                var cont = banana._settings.container,
                    searchForm = banana._appendSearch();

                cont.html("");
                banana.__settings.resultsContainer = $("<div>", {}).appendTo(cont);

                cont.prepend(searchForm);


                if (typeof banana._settings.iconsData == "string") {
                    var dataToSend={};
                    dataToSend[banana._settings.tokenName]=banana._settings.token;

                    $.getJSON(banana._settings.iconsData, dataToSend,
                        function (data) {
                            banana.__settings.iconsDataBuffer = data;
                            banana._parseIcons(banana.__settings.iconsDataBuffer);

                        }).fail(function () {
                            banana._displayError(banana._settings.i18n.iconsLoadError)
                        })
                } else {
                    banana.__settings.iconsDataBuffer = banana._settings.iconsData;
                    banana._parseIcons(banana.__settings.iconsDataBuffer);
                }

                setTimeout(function () {
                    $(searchForm).find('input')[0].focus();

                }, 300)

            },
            _searchIcons: function (keyword) {
                var results = [];
                $.each(banana.__settings.iconsDataBuffer, function (k, v) {

                    if (banana._findIcon(keyword, v.tags)) {
                        results.push(v);
                    }

                });

                return results;
            }, _findIcon: function (needle, haystack) {
                var items = 1;
                if (haystack == null)
                    return false;

                var _needle = needle.match(/\S+/g);

                $.each(_needle, function (nK, nV) {
                    $.each(haystack, function (k, v) {
                        if (v && nV.trim() != "") {

                            if (v.indexOf(nV.trim().toLowerCase()) == 0) {
                                items++;
                            }
                        }

                    });
                });


                return (items - _needle.length) > 0;
            },

            _parseIcons: function (icons) {
                var cont = banana.__settings.resultsContainer,
                    iconsList = $("<ul>", {
                        class: "bn_icons bananimatechilds"
                    });


                $.each(icons, function (k, v) {
                    iconsList.append($("<li>", {
                        html: $("<i>", {
                            class: banana._settings.iconsPrefix + v.name
                        }),
                        click: function () {
                            banana._select(banana._settings.iconsPrefix + v.name);
                        }

                    }));

                });

                cont.html("").append(iconsList);
            }

        };


        banana._init(settings);

        return this.each(function () {
            $(this).css({}).click(function (e) {
                e.stopPropagation();
                e.preventDefault();

                if (banana._settings.resetFolderOnStart) {
                    banana._settings.folder = "";
                } else {
                    banana._settings.container.scrollTop(0);
                }
                banana._getData();
                //$(window).unbind("resize",banana._move(this)).bind("resize",banana._move(this));
                banana._move(this);
                banana._attachWindowResize(this);

                banana._settings.container.parent().show();
                setTimeout(function () {
                    banana._settings.container.parent().removeClass('bye')
                }, 100)


            });

        });


    };


}(jQuery));