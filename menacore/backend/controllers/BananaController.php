<?php
namespace backend\controllers;

use yii\data\ActiveDataProvider;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 * *****************  PROUDLY  *****************
 *  __  __          _____  ______   _____ _   _
 * |  \/  |   /\   |  __ \|  ____| |_   _| \ | |
 * | \  / |  /  \  | |  | | |__      | | |  \| |
 * | |\/| | / /\ \ | |  | |  __|     | | | . ` |
 * | |  | |/ ____ \| |__| | |____   _| |_| |\  |
 * |_|__|_/_/  _ \_\_____/|______|_|_____|_| \_|
 * |  _ \| |  | |  __ \ / ____|/ __ \ / ____|
 * | |_) | |  | | |__) | |  __| |  | | (___
 * |  _ <| |  | |  _  /| | |_ | |  | |\___ \
 * | |_) | |__| | | \ \| |__| | |__| |____) |(SPAIN)
 * |____/ \____/|_|  \_\\_____|\____/|_____/
 *
 ******************* BY XENON *******************
 *
 * **********************************************
 *    PLEASE, CONSIDER THIS IS ONLY FOR
 *    DEMONSTRATION PURPOSES.
 *    IT´S RECOMMENDED TO ENSURE THAT
 *    YOUR OWN IMPLEMENTATION FOLLOWS
 *    ALL THE SECURITY REQUIREMENTS.
 * **********************************************
 * Copyright (c) 2016 Xenon Publicidad
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/*
 *
 *
 * ************ INFO ************
 *  Tree will try to generate all thumbnails that isn´t generated. It may be long time depending your folder structure.
 * May need to increase max_execution_time to prevent errors.
 *
 *
 */


/**
 * Initialize the class
 */


/**
 * Class bananaManager
 */
class BananaController extends Controller
{


    /**
     * Limit the folders that can be explored for security reasons
     * @var array
     */
    private $allowedFolders = array();
    /**
     * Block folders to prevent access. This rule is stronger than $allowedFolders
     * @var array
     */
    private $forbiddenFolders = [];
    /**
     * Default dir from where start
     * @var string
     */
    private $initialDir = "../upload/";


    private $dir = "";

    /**
     * Buffer for error messages
     * @var string
     */
    public $error = "";

    /**
     * Buffer for messages
     * @var string
     */
    public $info = "";

    /**
     * Folder to save and read thumbnails
     * @var string
     */
    private $thumbnailDir = "../thumbs/";

    /**
     * Base path of your project
     * @var string
     */
    private $rootFolder="../";

    /**
     * Width of auto-generated thumnail
     * @var int
     */
    private $thumbnailWidth = 150;

    /**
     * Allow file upload
     * @var bool
     */
    private $allowUpload = true;

    /**
     * List of allowed formats. Default all
     * @var array
     */
    private $allowedFormats = false;

    /**
     * Allow delete directories
     * @var bool
     */
    private $allowDeleteDirs = true;

    /**
     * Buffer for generated tree
     * @var array
     */
    private $tree = array();

    /**
     * List of image formats that CAN BE PROCESSED BY GD2. This is used to know when to generate a thumbnail.
     * @var array
     */
    private $imageFormats = array("jpg", "jpeg", "png", "gif", "bmp");

    /**
     * Internal folder counter. This is used by folder limit to prevent large data amount
     * @var int
     */
    private $foldersCounter = 0;

    /**
     * When the number of folders found in the tree is bigger than this an error will be thrown.
     * @var int
     */
    private $maxFolderLimit = 500;

    /**
     * If true: imagecopyresampled, false: imagecopyresized (faster, poor quality)
     * @var bool
     */
    private $resampledThumbail = true;

    /**
     * Used to format date. Check http://php.net/manual/es/function.date.php
     * @var string
     */
    private $dateFormat = 'd-m-Y';

    /**
     * Do not edit this var. Is populated with realpath;
     * @var string
     */
    private $rootPath="";


    /**
     * Full extensions lists thanks to fileinfo.com
     * There are two different arrays for each format. One with most common and other with full list.
     * List of extensions grouped by their common file type name
     * @var array
     */
    public static $FILES_TYPES = array(
        "IMAGE" => array("jpg", "jpeg", "png", "gif", "bmp"),
        "ALL_IMAGE" => ['001','2BP','360','411','73I','8CA','8CI','8PBS','8XI','9.PNG','ABM','ACCOUNTPICTURE-MS','ACORN','ACR','ADC','AFX','AGIF','AGP','AIC','AIS','ALBM','APD','APM','APNG','APS','APX','ARR','ART','ARTWORK','ARW','ASW','AVATAR','AVB','AWD','AWD','BLKRT','BLZ','BM2','BMC','BMF','BMP','BMQ','BMX','BMZ','BPG','BRK','BRN','BRT','BSS','BTI','BW','C4','CAL','CALS','CAM','CAN','CD5','CDC','CDG','CE','CIMG','CIN','CIT','CLIP','COLZ','CPBITMAP','CPC','CPD','CPG','CPS','CPT','CPX','CSF','CT','CUT','DC2','DCM','DCX','DDB','DDS','DDT','DGT','DIB','DIC','DICOM','DJV','DJVU','DM3','DMI','DPX','DRZ','DT2','DTW','DVL','ECW','EPP','EXR','FAC','FACE','FAL','FAX','FBM','FIL','FITS','FPG','FPOS','FPPX','FPX','FRM','G3','GBR','GCDP','GFB','GFIE','GGR','GIF','GIH','GIM','GMBCK','GMSPR','GP4','GPD','GRO','GROB','GRY','HDP','HDR','HDRP','HF','HPI','HR','HRF','I3D','IC1','IC2','IC3','ICA','ICB','ICN','ICON','ICPR','ILBM','IMG','IMJ','INFO','INK','INT','IPHOTOPROJECT','IPICK','IPX','ITC2','ITHMB','IVR','IVUE','IWI','J','J2C','J2K','JAS','JB2','JBF','JBG','JBIG','JBIG2','JBMP','JBR','JFI','JFIF','JIA','JIF','JIFF','JNG','JP2','JPC','JPD','JPE','JPEG','JPF','JPG','JPG2','JPS','JPX','JTF','JWL','JXR','KDI','KDK','KFX','KIC','KODAK','KPG','LB','LBM','LIF','LIP','LJP','LRPREVIEW','LZP','MAC','MAT','MAX','MBM','MBM','MCS','MET','MIC','MIFF','MIP','MIX','MNG','MNR','MPF','MPO','MRB','MRXS','MSK','MSP','MXI','MYL','NCD','NCR','NCT','NEO','NLM','NOL','OC3','OC4','OC5','OCI','ODI','OMF','OPLC','ORA','OTA','OTB','OTI','OZB','OZJ','OZT','PAC','PAL','PANO','PAP','PAT','PBM','PC1','PC2','PC3','PCD','PCX','PDD','PDN','PE4','PE4','PFI','PFR','PGF','PGM','PI1','PI2','PI2','PI3','PI4','PI5','PI6','PIC','PIC','PIC','PICNC','PICT','PICTCLIPPING','PISKEL','PIX','PIX','PIXADEX','PJPEG','PJPG','PM','PM3','PMG','PNG','PNI','PNM','PNS','PNT','PNTG','POP','POV','POV','PP4','PP5','PPF','PPM','PRW','PSB','PSD','PSDX','PSE','PSF','PSP','PSPBRUSH','PSPIMAGE','PTG','PTK','PTS','PTX','PTX','PVR','PWP','PX','PXD','PXICON','PXM','PXR','PYXEL','PZA','PZP','PZS','QIF','QMG','QTI','QTIF','RAS','RCL','RCU','RGB','RGB','RGBA','RGF','RIC','RIF','RIFF','RIX','RLE','RLI','RPF','RRI','RS','RSB','RSR','RTL','RVG','S2MV','SAI','SAR','SBP','SCG','SCI','SCN','SCP','SCT','SCU','SDR','SEP','SFC','SFF','SFW','SGD','SGI','SHG','SID','SID','SIG','SIG','SIM','SKITCH','SKM','SKYPEEMOTICONSET','SLD','SMP','SOB','SPA','SPC','SPE','SPH','SPIFF','SPJ','SPP','SPR','SPRITE','SPRITE2','SPU','SR','STE','SUMO','SUN','SUNIFF','SUP','SVA','SVM','T2B','TAAC','TARGA','TB0','TBN','TEX','TFC','TG4','TGA','THM','THM','THUMB','TIF','TIF','TIFF','TJP','TM2','TN','TN1','TN2','TN3','TNY','TPF','TPI','TPS','TRIF','TSR','TUB','U','UFO','UGA','UGOIRA','URT','USERTILE-MS','V','VDA','VFF','VIC','VICAR','VIFF','VNA','VPE','VRIMG','VRPHOTO','VSS','VST','WB0','WB1','WB2','WBC','WBD','WBM','WBMP','WBP','WBZ','WDP','WEBP','WI','WIC','WMP','WPB','WPE','WVL','XBM','XCF','XPM','XWD','Y','YSP','YUV','ZIF'],
        "ALL_RAW_IMAGE"=>['3FR','ARI','ARW','BAY','CR2','CRW','CXI','DCR','DNG','EIP','ERF','FFF','IIQ','J6I','K25','KDC','MEF','MFW','MOS','MRW','NEF','NRW','ORF','PEF','RAF','RAW','RW2','RWL','RWZ','SR2','SRF','SRW','X3F'],

        "TEXT" => array("doc", "docx", "log", "msg", "odt", "pages", "rtf", "tex", "txt", "wpd", "wps"),
        "ALL_TEXT" => ["1ST","ABW","ACT","ADOC","AIM","ANS","APKG","APT","ASC","ASC","ASCII","ASE","ATY","AWP","AWT","AWW","BAD","BBS","BDP","BDR","BEAN","BIB","BIB","BIBTEX","BML","BNA","BOC","BRX","BTD","BZABW","CALCA","CHARSET","CHART","CHORD","CNM","COD","CRWL","CWS","CYI","DCA","DFTI","DGS","DIZ","DNE","DOC","DOC","DOCM","DOCX","DOCXML","DOCZ","DOTM","DOTX","DOX","DROPBOX","DSC","DVI","DWD","DX","DXB","DXP","EIO","EIT","EMF","EML","EMLX","EMULECOLLECTION","EPP","ERR","ERR","ETF","ETX","EUC","FADEIN.TEMPLATE","FAQ","FBL","FCF","FDF","FDR","FDS","FDT","FDX","FDXT","FFT","FLR","FODT","FOUNTAIN","FPT","FRT","FWD","FWDN","GDOC","GMD","GPD","GPN","GSD","GTHR","GV","HBK","HHT","HS","HWP","HWP","HZ","IDX","IIL","IPF","IPSPOT","JARVIS","JIS","JNP","JOE","JP1","JRTF","KES","KLG","KLG","KNT","KON","KWD","LATEX","LBT","LIS","LNT","LOG","LP2","LST","LST","LTR","LTX","LUE","LUF","LWP","LXFML","LYT","LYX","MAN","MBOX","MCW","MD5.TXT","ME","MELL","MELLEL","MIN","MNT","MSG","MW","MWD","MWP","NB","NDOC","NFO","NGLOSS","NJX","NOTES","NOW","NWCTXT","NWM","NWP","OCR","ODIF","ODM","ODO","ODT","OFL","OPENBSD","ORT","OTT","P7S","PAGES","PAGES-TEF","PDPCMD","PFS","PFX","PJT","PLANTUML","PMO","PRT","PRT","PSW","PU","PVJ","PVM","PWD","PWDP","PWDPL","PWI","PWR","QDL","QPF","RAD","README","RFT","RIS","RPT","RST","RTD","RTF","RTFD","RTX","RUN","RZK","RZN","SAF","SAFETEXT","SAM","SAM","SAVE","SCC","SCM","SCRIV","SCRIVX","SCT","SCW","SDM","SDOC","SDW","SE","SESSION","SGM","SIG","SKCARD","SLA","SLA.GZ","SMF","SMS","SSA","STORY","STRINGS","STW","STY","SUB","SUBLIME-PROJECT","SUBLIME-WORKSPACE","SXG","SXW","TAB","TAB","TDF","TDF","TEMPLATE","TEX","TEXT","TEXTCLIPPING","THP","TLB","TM","TMD","TMV","TPC","TRELBY","TVJ","TXT","U3I","UNAUTH","UNX","UOF","UOT","UPD","UTF8","UTXT","VCT","VNT","VW","WBK","WEBDOC","WN","WP","WP4","WP5","WP6","WP7","WPA","WPD","WPD","WPD","WPL","WPS","WPS","WPT","WPT","WPW","WRI","WSD","WTT","WTX","XBDOC","XBPLATE","XDL","XDL","XWP","XWP","XWP","XY","XY3","XYP","XYW","ZABW","ZRTF","ZW"],

        "SPREADSHEET" => array("csv", "xlr", "xls", "xlsx","numbers"),
        "ALL_SPREADSHEET" => ['123','AST','AWS','BKS','DEF','DEX','DFG','DIS','EDX','EDXZ','ESS','FCS','FM','FODS','FP','GNM','GNUMERIC','GSHEET','IMP','MAR','NB','NCSS','NMBTEMPLATE','NUMBERS','NUMBERS-TEF','ODS','OTS','PMD','PMV','QPW','RDF','SDC','STC','SXC','TMV','TMVT','UOS','WKI','WKS','WKS','WKU','WQ1','WQ2','XAR','XL','XLR','XLS','XLSB','XLSHTML','XLSM','XLSMHTML','XLSX','XLTHTML','XLTM','XLTX'],

        "PRESENTATION" => array("ppt", "key", "pps", "pptx"),

        "VIDEO" => array("3g2", "3gp", "asf", "asx", "avi", "flv", "m4v", "mov", "mp4", "mpg", "rm", "srt", "swf", "vob", "wmv"),
        "ALL_VIDEO"=>['264','3G2','3GP','3GP2','3GPP','3GPP2','3MM','3P2','60D','787','890','AAF','AEC','AEP','AEPX','AET','AETX','AJP','ALE','AM','AMC','AMV','AMX','ANIM','ANX','AQT','ARCUT','ARF','ASF','ASX','AVB','AVC','AVCHD','AVD','AVI','AVM','AVP','AVS','AVS','AVV','AWLIVE','AXM','AXV','BDM','BDMV','BDT2','BDT3','BIK','BIN','BIX','BMC','BMK','BNP','BOX','BS4','BSF','BU','BVR','BYU','CAMPROJ','CAMREC','CAMV','CED','CEL','CINE','CIP','CLK','CLPI','CMMP','CMMTPL','CMPROJ','CMREC','CMV','CPI','CPVC','CST','CVC','CX3','D2V','D3V','DASH','DAT','DAV','DB2','DCE','DCK','DCR','DCR','DDAT','DIF','DIR','DIVX','DLX','DMB','DMSD','DMSD3D','DMSM','DMSM3D','DMSS','DMX','DNC','DPA','DPG','DREAM','DSY','DV','DV-AVI','DV4','DVDMEDIA','DVR','DVR-MS','DVX','DXR','DZM','DZP','DZT','EDL','EVO','EVO','EXO','EYE','EYETV','EZT','F4F','F4P','F4V','FBR','FBR','FBZ','FCARCH','FCP','FCPROJECT','FFD','FFM','FLC','FLH','FLI','FLV','FLX','FPDX','FTC','G64','GCS','GFP','GIFV','GL','GOM','GRASP','GTS','GVI','GVP','GXF','H264','HDMOV','HDV','HKM','IFO','IMOVIELIBRARY','IMOVIEMOBILE','IMOVIEPROJ','IMOVIEPROJECT','INP','INT','IRCP','IRF','ISM','ISMC','ISMCLIP','ISMV','IVA','IVF','IVR','IVS','IZZ','IZZY','JMV','JSS','JTS','JTV','K3G','KDENLIVE','KMV','KTN','LREC','LRV','LSF','LSX','LVIX','M15','M1PG','M1V','M21','M21','M2A','M2P','M2T','M2TS','M2V','M4E','M4U','M4V','M75','MANI','META','MGV','MJ2','MJP','MJPEG','MJPG','MK3D','MKV','MMV','MNV','MOB','MOD','MODD','MOFF','MOI','MOOV','MOV','MOVIE','MP21','MP21','MP2V','MP4','MP4.INFOVID','MP4V','MPE','MPEG','MPEG1','MPEG2','MPEG4','MPF','MPG','MPG2','MPG4','MPGINDEX','MPL','MPL','MPLS','MPROJ','MPSUB','MPV','MPV2','MQV','MSDVD','MSE','MSH','MSWMM','MT2S','MTS','MTV','MVB','MVC','MVD','MVE','MVEX','MVP','MVP','MVY','MXF','MXV','MYS','NCOR','NSV','NTP','NUT','NUV','NVC','OGM','OGV','OGX','ORV','OSP','OTRKEY','PAC','PAR','PDS','PGI','PHOTOSHOW','PIV','PJS','PLAYLIST','PLPROJ','PMF','PMV','PNS','PPJ','PREL','PRO','PRO4DVD','PRO5DVD','PROQC','PRPROJ','PRTL','PSB','PSH','PSSD','PVA','PVR','PXV','QT','QTCH','QTINDEX','QTL','QTM','QTZ','R3D','RCD','RCPROJECT','RCREC','RCUT','RDB','REC','RM','RMD','RMD','RMP','RMS','RMV','RMVB','ROQ','RP','RSX','RTS','RTS','RUM','RV','RVID','RVL','SAN','SBK','SBT','SBZ','SCC','SCM','SCM','SCN','SCREENFLOW','SDV','SEC','SEC','SEDPRJ','SEQ','SFD','SFERA','SFVIDCAP','SIV','SMI','SMI','SMIL','SMK','SML','SMV','SNAGPROJ','SPL','SQZ','SRT','SSF','SSM','STL','STR','STX','SVI','SWF','SWI','SWT','TDA3MT','TDT','TDX','THEATER','THP','TID','TIVO','TIX','TOD','TP','TP0','TPD','TPR','TREC','TRP','TS','TSP','TTXT','TVLAYER','TVRECORDING','TVS','TVSHOW','USF','USM','VBC','VC1','VCPF','VCR','VCV','VDO','VDR','VDX','VEG','VEM','VEP','VF','VFT','VFW','VFZ','VGZ','VID','VIDEO','VIEWLET','VIV','VIVO','VIX','VLAB','VMLF','VMLT','VOB','VP3','VP6','VP7','VPJ','VRO','VS4','VSE','VSP','VTT','W32','WCP','WEBM','WFSP','WGI','WLMP','WM','WMD','WMMP','WMV','WMX','WOT','WP3','WPL','WSVE','WTV','WVE','WVX','WXP','XEJ','XEL','XESC','XFL','XLMV','XML','XMV','XVID','Y4M','YOG','YUV','ZEG','ZM1','ZM2','ZM3','ZMV'],

        "VECTOR" => array("ai", "eps", "svg", "ps", "cdr"),
        "ALL_VECTOR" => ['ABC','AC5','AC6','AF2','AF3','AFDESIGN','AI','ART','ARTB','ASY','AWG','CAG','CCX','CDD','CDDZ','CDLX','CDMM','CDMT','CDMTZ','CDMZ','CDR','CDS','CDSX','CDT','CDTX','CDX','CDX','CGM','CIL','CLARIFY','CMX','CNV','COR','CSY','CV5','CVG','CVI','CVS','CVX','CWT','CXF','DCS','DDRW','DED','DESIGN','DHS','DIA','DPP','DPR','DPX','DRAWING','DRAWIT','DRW','DRW','DSF','DXB','EGC','EMF','EMZ','EP','EPS','EPSF','EZDRAW','FH10','FH11','FH3','FH4','FH5','FH6','FH7','FH8','FH9','FHD','FIF','FIG','FMV','FS','FT10','FT11','FT7','FT8','FT9','FTN','FXG','GDRAW','GEM','GKS','GLOX','GLS','GRAFFLE','GSD','GSTENCIL','GTEMPLATE','HGL','HPG','HPGL','HPL','IDEA','IGT','IGX','IMD','INK','INK','LMK','MGC','MGCB','MGMF','MGMT','MGMX','MGS','MGTX','MMAT','MP','MVG','NAP','ODG','OTG','OVP','OVR','PAT','PCS','PD','PEN','PFD','PFV','PL','PLT','PLT','PMG','POBJ','PS','PSID','PWS','RDL','SCV','SDA','SK1','SK2','SKETCH','SLDDRT','SMF','SNAGITSTAMPS','SNAGSTYLES','SSK','STD','STN','SVF','SVG','SVGZ','SXD','TLC','TNE','TPL','UFR','VBR','VEC','VML','VSD','VSDM','VSDX','VST','VSTM','VSTX','WMF','WMZ','WPG','WPI','XAR','XMIND','XMMAP','XPR','YAL','ZGM'],

        "AUDIO" => array("wav","mp3","ogg"),
        "ALL_AUDIO" => ['3GA','4MP','5XB','5XE','5XS','669','8SVX','A2B','A2I','A2M','A2P','A2T','A2W','AA','AA3','AAC','AAX','ABC','ABM','AC3','ACD','ACD-BAK','ACD-ZIP','ACM','ACT','ADG','ADT','ADTS','AFC','AGM','AGR','AHX','AIF','AIFC','AIFF','AIMPPL','AKP','ALAW','ALC','ALS','AMF','AMR','AMS','AMS','AMXD','AMZ','ANG','AOB','APE','APF','APL','ASD','AT3','AU','AU','AUD','AUP','AVASTSOUNDS','AXA','BAND','BAP','BDD','BIDULE','BMML','BNK','BRR','BUN','BWF','BWG','BWW','CAF','CAFF','CDA','CDDA','CDLX','CDO','CDR','CEL','CFA','CGRP','CIDB','CKB','CKF','CMF','CONFORM','COPY','CPR','CPT','CSH','CTS','CWB','CWP','CWS','CWT','DCF','DCM','DCT','DEWF','DF2','DFC','DFF','DIG','DIG','DJR','DLS','DM','DMC','DMF','DMSA','DMSE','DRA','DRG','DS2','DSF','DSM','DSS','DTM','DTS','DTSHD','DVF','DW','DWD','EFA','EFE','EFK','EFQ','EFS','EFV','EMD','EMP','EMX','EMY','EOP','ERB','ESPS','F2R','F32','F3R','F4A','F64','FAR','FDP','FEV','FLAC','FLM','FLP','FLP','FPA','FRG','FSB','FSC','FSM','FTI','FTM','FTM','FTMX','FUZ','FZF','FZV','G721','G723','G726','GBS','GIG','GMC','GP5','GPBANK','GPK','GPX','GROOVE','GSF','GSFLIB','GSM','GYM','H0','H3B','H3E','H4B','H4E','H5B','H5E','H5S','HBB','HBE','HBS','HDP','HMA','HPS','HSB','IAA','ICS','IFF','IGP','IMP','INS','INS','ISMA','IT','ITI','ITLS','K26','KAR','KFN','KOZ','KOZ','KPL','KRZ','KSD','KSF','KT3','LA','LOGIC','LOGICX','LSO','LVP','LWV','M','M2','M3U','M3U8','M4A','M4B','M4P','M4R','MA1','MBR','MDC','MDR','MED','MGV','MID','MIDI','MINIGSF','MINIPSF','MINIPSF2','MINIUSF','MKA','MMF','MMLP','MMM','MMP','MMP','MMPZ','MO3','MOD','MOGG','MP2','MP3','MP_','MPA','MPC','MPDP','MPGA','MPU','MSCX','MSCZ','MSV','MTE','MTF','MTI','MTM','MTP','MTS','MU3','MUI','MUS','MUS','MUS','MUSX','MUX','MUX','MX3','MX4','MX5','MX5TEMPLATE','MXL','MXMF','MYR','NARRATIVE','NBS','NCW','NKB','NKC','NKI','NKM','NKS','NKX','NML','NMSV','NOTE','NRA','NRT','NSA','NST','NTN','NWC','OBW','ODM','OGA','OGG','OKT','OMA','OMF','OMG','OMX','OPUS','OTS','OVE','OVW','PANDORA','PCA','PCAST','PCG','PCM','PEAK','PEK','PJUNOXL','PK','PKF','PLA','PLS','PLST','PLY','PNA','PNO','PPC','PSF','PSF1','PSF2','PSM','PTCOP','PTF','PTM','PTS','PTT','PTX','PTXT','PVC','Q1','Q2','QCP','R1M','RA','RAD','RAM','RAX','RBS','REX','RFL','RGRP','RIP','RMI','RMJ','RMX','RNG','RNS','ROL','RSF','RSN','RSO','RTA','RTI','RX2','S3I','S3M','SAF','SAP','SBG','SBI','SC2','SCS11','SD','SD','SD2','SDAT','SDS','SEQ','SES','SESX','SF2','SFK','SFL','SFPACK','SFZ','SGP','SHN','SIB','SLP','SLX','SMA','SMF','SMP','SMP','SMPX','SND','SND','SNG','SNG','SNS','SOU','SPH','SPPACK','SPRG','SSEQ','SSEQ','SSM','SSND','STAP','STM','STX','STY','SVD','SVQ','SVX','SWA','SXT','SYH','SYN','SYW','SYX','TAK','TD0','TG','THX','TOC','TRAK','TSP','TTA','TXW','U','UAX','UB','ULT','UNI','USF','USFLIB','UST','UW','UWF','V2M','VAG','VAP','VC3','VCE','VIP','VLC','VMD','VMF','VMO','VOC','VOX','VOXAL','VPL','VPM','VPW','VQF','VRF','VSQ','VSQX','VTX','VYF','W01','W64','WAV','WAV','WAVE','WAX','WEM','WFB','WFD','WFM','WFP','WMA','WOW','WPK','WPP','WPROJ','WRK','WUS','WUT','WV','WVC','WVE','WWU','XA','XBMML','XFS','XM','XMI','XMS','XMU','XMZ','XPF','XRNS','XSP','XSPF','YOOKOO','ZGR','ZPL','ZVD'],

        "PDF" => array("pdf"),

        "CAD" => array("dwg", "dxf"),
        "ALL_CAD" => ["123","123C","123D","123DX","2D","3DC","3DL","A2L","ACT","ADI","ADT","AFD","AFS","ANY","ARD","ART","ASC","ASM","ASY","ATT","BBCD","BCD","BDC","BDL","BIT","BLK","BMF","BPM","BPMC","BPZ","BRD","BRD","BXL","CAD","CAM","CATDRAWING","CATPART","CATPRODUCT","CDDX","CDL","CDW","CEL","CF2","CFF","CGR","CIB","CIRCUIT","CKD","CMP","CMP","CNC","CNC","CND","CPA","CRV","CYP","CZD","DB1","DBQ","DC","DC1","DC2","DC2","DC3","DCD","DES","DFT","DFX","DGB","DGK","DGN","DLV","DLX","DRA","DRA","DRG","DRU","DRWDOT","DSG","DSN","DST","DST","DVG","DWFX","DWG","DWS","DWT","DXE","DXF","DXX","EASM","EDF","EDN","EDRW","ELD","EPF","EQN","EWB","EWD","EXB","EZC","EZP","FAN","FCD","FCSTD","FCW","FLX","FMZ","FNC","FPD","FPP","FZ","FZB","FZM","FZP","FZZ","G","G3D","GBX","GCD","GDS","GINSPECT_PRJ","GSM","GXC","GXD","GXH","GXM","HCP","HSC","HSF","HUS","IAM","IBA","IC3D","ICD","ICS","IDE","IDV","IDW","IF","IFCXML","IFCZIP","IGS","IPF","IPJ","IPN","IPT","ISE","ISO","ISOZ","JAM","JBC","JOB","JT","JVSG","KIT","LCF","LDR","LDT","LI3D","LIN","LIZD","LTL","LYC","LYR","MC9","MCD","MCX","MDL","MHS","MIN","MIN","MOD","MODEL","MODFEM","MP10","MP11","MP7","MP9","MS11","MS7","MS9","MSM","MVS","NC","NEU","NGC","NGD","NPL","NWC","NWD","NWF","OLB","OPJ","OPT","PAT","PC6","PC7","PCS","PHJ","PHO","PIPD","PIPE","PLA","PLN","PM3","PRG","PRO","PRT","PRT","PRT","PRT","PSF","PSM","PSS","PSU","PSV","PWD","PWT","QPM","RCD","RDF","RED","RIG","RML","RRA","RS","RSG","RSM","RTD","SAB","SAT","SBP","SCAD","SCDOC","SCH","SCH","SDG","SEW","SHX","SKF","SLDASM","SLDDRW","SLDPRT","SPT","STL","SVD","SYM","T3001","TAK","TBP","TC2","TC3","TCD","TCD","TCM","TCP","TCT","TCW","TCX","TOP","TOPPRJ","TOPVIW","TSC","TSF","ULD","UNT","UPF","VET","VND","VTF","VWX","WDP","X_B","X_T","XISE","XV3"],

        "GIS" => array("gpx", "kml", "kmz"),
        "ALL_GIS"=>['3D','3DC','3DD','3DL','477','ADF','APL','APR','AQM','AT5','ATX','AUX','AVL','AXE','AXT','BIL','BPW','BT','COR','CSF','CUB','CVI','DEM','DIV','DIX','DLG','DMF','DMT','DT0','DT1','DT2','DVC','E00','EMBR','ERS','EST','ETA','FBL','FDS','FFS','FIT','FLS','FME','FMI','FMV','FMW','GEOJSON','GFW','GLB','GMAP','GMF','GML','GPF','GPRX','GPS','GPX','GRB','GSB','GSI','GSM','GSR','GSR2','GST','GWS','HDR','HGT','IMD','IMG','IMG','IMI','JGW','JPGW','JPR','JPW','KML','KMZ','LAN','LPK','MAP','MAP','MDC','MGM','MID','MIF','MMM','MMZ','MNH','MPK','MPS','MSD','MWX','MXD','MXT','NGT','NM2','NM3','NMAP','NMC','NMF','NV2','OCD','OSB','OSC','OSM','OV2','PIN','PIX','PMF','PRM','PTM','PTT','RDC','RDF','REF','RGN','RMP','RRD','RST','SAF','SBN','SBN','SDF','SDM','SDW','SHP','SLD','SMM','SMP','SP3','SSF','STT','STYLE','SVX','SXD','SYM','TAB','TFRD','TFW','TH','TPX','TTKGP','VCT','VDC','VEC','WFD','WLD','WLX','WOR','XOL'],

        "FONT" => array("fnt", "fon", "otf", "ttf"),
        "ALL_FONT" => ['ABF','ACFM','AFM','AMFM','BDF','CHA','CHR','COMPOSITEFONT','DFONT','EOT','ETX','EUF','F3F','FFIL','FNT','FON','FOT','GDR','GF','GXF','LWFN','MCF','MF','MXF','NFTR','ODTTF','OTF','PCF','PFA','PFB','PFM','PFR','PK','PMT','SFD','SFP','SUIT','T65','TFM','TTC','TTE','TTF','TXF','VFB','VLW','VNF','WOFF','XFN','XFT','YTF'],

        "COMPRESSED" => array("7z", "cbr", "deb", "gz", "pkg", "bz", "bz2", "rar", "rpm", "sitx", "tar.gz", "zip", "zipx"),
        "ALL_COMPRESSED" => ["0","000","7Z","7Z.001","7Z.002","A00","A01","A02","ACE","AGG","AIN","ALZ","APZ","AR","ARC","ARCHIVER","ARH","ARI","ARJ","ARK","ASR","B1","B64","BA","BH","BNDL","BOO","BUNDLE","BZ","BZ2","BZA","BZIP","BZIP2","C00","C01","C02","C10","CAR","CB7","CBA","CBR","CBT","CBZ","CDZ","COMPPKG.HAUPTWERK.RAR","COMPPKG_HAUPTWERK_RAR","CP9","CPGZ","CPT","CXARCHIVE","CZIP","DAR","DD","DEB","DGC","DIST","DL_","DZ","ECS","EFW","EGG","EPI","F","FDP","FP8","FZBZ","FZPZ","GCA","GMZ","GZ","GZ2","GZA","GZI","GZIP","HA","HBC","HBC2","HBE","HKI","HKI1","HKI2","HKI3","HPK","HYP","IADPROJ","ICE","IPG","IPK","ISH","ISX","ITA","IZE","J","JAR.PACK","JGZ","JIC","JSONLZ4","KGB","KZ","LAYOUT","LBR","LEMON","LHA","LIBZIP","LNX","LQR","LZ","LZH","LZM","LZMA","LZO","LZX","MD","MINT","MOU","MPKG","MZP","MZP","NEX","NZ","OAR","OZ","P01","P19","PACK.GZ","PACKAGE","PAE","PAK","PAQ6","PAQ7","PAQ8","PAQ8F","PAQ8L","PAQ8P","PAR","PAR2","PAX","PBI","PCV","PEA","PET","PF","PIM","PIT","PIZ","PKG","PSZ","PUP","PUP","PUZ","PWA","QDA","R0","R00","R01","R02","R03","R1","R2","R21","R30","RAR","REV","RK","RNC","RP9","RPM","RTE","RZ","S00","S01","S02","S7Z","SAR","SBX","SDC","SDN","SEA","SEN","SFG","SFS","SFX","SH","SHAR","SHK","SHR","SIFZ","SIT","SITX","SMPF","SNAPPY","SNB","SPT","SQX","SREP","STPROJ","SY_","TAR.BZ2","TAR.GZ","TAR.GZ2","TAR.LZ","TAR.LZMA","TAR.XZ","TAR.Z","TAZ","TBZ","TBZ2","TG","TGZ","TLZ","TLZMA","TRS","TX_","TXZ","TZ","UC2","UFS.UZIP","UHA","UZIP","VEM","VSI","WA","WAFF","WAR","WLB","WOT","XAR","XEF","XEZ","XMCDZ","XX","XZ","XZM","Y","YZ","YZ1","Z","Z01","Z02","Z03","Z04","ZAP","ZFSENDTOTARGET","ZI","ZIP","ZIPX","ZIX","ZL","ZOO","ZPI","ZSPLIT","ZW","ZZ"],

        "BACKUP" => array("bak"),
        "ALL_BACKUP" => ['$$$','$DB',"001","001","002","003","113","73B","__A","__B","AB","ABA","ABBU","ABF","ABK","ABU","ACP","ACR","ADI","ADI","AEA","AFI","ARC","ARC","AS4","ASD","ASHBAK","ASV","ASVX","ATE","ATI","BAC","BACKUP","BACKUPDB","BAK","BAK","BAK","BAK","BAK2","BAK3","BAKX","BAK~","BBB","BBZ","BCK","BCKP","BCM","BDB","BFF","BIF","BIFX","BK1","BK1","BKC","BKF","BKP","BKP","BKUP","BKZ","BLEND1","BLEND2","BM3","BMK","BPA","BPB","BPM","BPN","BPS","BUP","BUP","CAA","CBK","CBS","CBU","CENON~","CK9","CMF","CRDS","CSD","CSM","DA0","DASH","DBA","DBK","DBK","DIM","DIY","DNA","DOV","DPB","DSB","FBC","FBF","FBK","FBK","FBU","FBW","FH","FHF","FLKA","FLKB","FPSX","FTMB","FUL","FWBACKUP","FZA","FZB","GB1","GB2","GBP","GHO","GHS","IBK","ICBU","ICF","INPROGRESS","IPD","IV2I","J01","JBK","JDC","JPA","JPS","KB2","LCB","LLX","MBF","MBK","MBW","MDBACKUP","MDDATA","MDINFO","MEM","MIG","MPB","MSIM","MV_","NB7","NBA","NBAK","NBD","NBD","NBF","NBF","NBI","NBK","NBK","NBS","NBU","NCO","NDA","NFB","NFC","NPF","NPS","NRBAK","NRS","NWBAK","OBK","OEB","OLD","ONEPKG","ORI","ORIG","OYX","PAQ","PBA","PBB","PBD","PBF","PBF","PBJ","PBX5SCRIPT","PBXSCRIPT","PDB","PQB","PQB-BACKUP","PRV","PSA","PTB","PVC","PVHD","QBA.TLG","QBB","QBK","QBM","QBMB","QBMD","QBX","QIC","QSF","QUALSOFTCODE","QUICKEN2015BACKUP","QUICKENBACKUP","QV~","RBC","RBF","RBF","RBF","RBK","RBS","RDB","RGMB","RMBAK","RRR","SAV","SBB","SBS","SBU","SDC","SIM","SIS","SKB","SME","SN1","SN2","SNA","SNS","SPF","SPG","SPI","SPS","SQB","SRR","STG","SV$","SV2I","TBK","TDB","TIBKP","TIG","TIS","TLG","TMP","TMP","TMR","TRN","TTBK","UCI","V2I","VBK","VBM","VBOX-PREV","VPCBACKUP","VRB","WALLETX","WBB","WBCAT","WBK","WIN","WIN","WJF","WPB","WSPAK","WX","XBK","XLK","YRCBCK","~CW"],

        "DB" => array("accdb", "db", "dbf", "mdb", "pdb", "sql"),
        "ALL_DB" => ['$ER','4DD','4DL','^^^','ABCDDB','ABS','ABX','ACCDB','ACCDC','ACCDE','ACCDR','ACCDT','ACCDW','ACCFT','ADB','ADB','ADE','ADF','ADN','ADP','ALF','ASK','BTR','CAT','CDB','CDB','CDB','CKP','CMA','CPD','CRYPT5','CRYPT6','CRYPT7','CRYPT8','DACONNECTIONS','DACPAC','DAD','DADIAGRAMS','DASCHEMA','DB','DB','DB-SHM','DB-WAL','DB.CRYPT8','DB2','DB3','DBC','DBF','DBS','DBT','DBV','DBX','DCB','DCT','DCX','DDL','DP1','DQY','DSK','DSN','DTSX','DXL','ECO','ECX','EDB','EPIM','FCD','FDB','FIC','FLEXOLIBRARY','FM5','FMP','FMP12','FMPSL','FOL','FP3','FP4','FP5','FP7','FPT','FRM','GDB','GDB','GWI','HDB','HIS','IB','IDB','IHX','ITDB','ITW','JET','JTX','KDB','KEXI','KEXIC','KEXIS','LGC','MAF','MAQ','MAR','MARSHAL','MAS','MAV','MAW','MDB','MDBHTML','MDF','MDN','MDT','MPD','MRG','MUD','MWB','MYD','NDF','NNT','NRMLIB','NS2','NS3','NS4','NSF','NV','NV2','NYF','ODB','ODB','OQY','ORA','ORX','OWC','P96','P97','PAN','PDB','PDB','PDM','PNZ','QRY','QVD','RBF','RCTD','ROD','ROD','RODX','RPD','RSD','SAS7BDAT','SBF','SCX','SDB','SDB','SDB','SDB','SDF','SIS','SPQ','SQL','SQLITE','SQLITE3','SQLITEDB','TE','TEACHER','TMD','TPS','TRC','TRC','TRM','UDB','UDL','USR','V12','VIS','VPD','WDB','WMDB','WRK','XDB','XLD','XMLFF'],

        "TEMPLATES" => array("tpl","twig"),

        "WEB" => array("css","html","js","scss","sass"),
        "ALL_WEB"=>['A4P','A5W','ADR','AEX','ALX','AN','AP','APPCACHE','ARO','ASA','ASAX','ASCX','ASHX','ASMX','ASP','ASPX','ASR','ATOM','ATT','AWM','AXD','BML','BOK','BROWSER','BTAPP','BWP','CCBJS','CDF','CER','CFM','CFML','CHA','CHAT','CHM','CMS','CODASITE','COMPRESSED','CON','CPG','CPHD','CRL','CRT','CSHTML','CSP','CSR','CSS','DAP','DBM','DCR','DER','DHTML','DISCO','DISCOMAP','DLL','DML','DO','DOCHTML','DOCMHTML','DOTHTML','DOWNLOAD','DWT','ECE','EDGE','EPIBRW','ESPROJ','EWP','FCGI','FMP','FREEWAY','FWP','FWTB','FWTEMPLATE','FWTEMPLATEB','GNE','GSP','HDM','HDML','HTACCESS','HTC','HTM','HTML','HTX','HXS','HYPE','HYPERESOURCES','HYPESYMBOL','HYPETEMPLATE','IDC','IQY','ITMS','ITPC','IWDGT','JCZ','JHTML','JNLP','JS','JSF','JSON','JSP','JSPA','JSPX','JSS','JST','JVS','JWS','KIT','LASSO','LBC','LESS','MAFF','MAP','MAPX','MASTER','MHT','MHTML','MOZ','MSPX','MUSE','MVC','MVR','NOD','NXG','NZB','OAM','OBML','OBML15','OBML16','OGNC','OLP','OPML','OTH','P12','P7','P7B','P7C','PAC','PAGE','PEM','PHP','PHP2','PHP3','PHP4','PHP5','PHTM','PHTML','PPTHTML','PPTMHTML','PRF','PRO','PSP','PTW','PUB','QBO','QF','QRM','RFLW','RHTML','RJS','RSS','RT','RW3','RWP','RWSW','RWTHEME','SASS','SAVEDDECK','SCSS','SDB','SEAM','SHT','SHTM','SHTML','SITE','SITEMAP','SITES','SITES2','SPC','SRF','SSP','STC','STL','STM','STML','STP','SUCK','SVC','SVR','SWZ','TVPI','TVVI','UCF','UHTML','URL','VBD','VBHTML','VDW','VLP','VRML','VRT','VSDISCO','WBS','WBXML','WDGT','WEB','WEBARCHIVE','WEBARCHIVEXML','WEBBOOKMARK','WEBHISTORY','WEBLOC','WEBSITE','WGP','WGT','WHTT','WIDGET','WML','WN','WOA','WPP','WPX','WRF','WSDL','XBEL','XBL','XFDL','XHT','XHTM','XHTML','XPD','XSS','XUL','XWS','ZFO','ZHTML','ZHTML','ZUL','ZVZ'],

        "CALENDAR" => array("ics","vcs"),

        "HDRI" => array("hdri"),

        "ALL_EBOOK"=>['ACSM','AEP','APNX','AVA','AZW','AZW1','AZW3','AZW4','BKK','BPNUEB','CBC','CEB','DNL','EA','EAL','EBK','EDN','EPUB','ETD','FB2','FKB','HTML0','HTMLZ','HTXT','HTZ4','HTZ5','IBOOKS','KOOB','LIT','LRF','LRS','LRX','MART','MBP','MOBI','NCX','NVA','OEB','OEBZIP','OPF','PEF','PHL','PML','PMLZ','POBI','PRC','QMK','RZB','RZS','SNB','TCR','TK3','TPZ','TR','TR3','VBK','WEBZ','YBK'],

        "ALL_DEVELOPER"=>['4DB','4TH','A','A2W','ABC','ACD','ADDIN','ADS','AGI','AIA','ALB','AM4','AM6','AM7','ANE','AP_','APA','APPX','APPXUPLOAD','APS','ARSC','ARTPROJ','AS','AS2PROJ','AS3PROJ','ASC','ASI','ASM','ASM','ASVF','AU3','AUTOPLAY','AWK','B','BAS','BB','BBC','BBPROJECT','BBPROJECTD','BCP','BDSPROJ','BET','BLUEJ','BPG','BPL','BRX','BS2','BSC','BUILDSETTING','C','C','CAF','CAPROJ','CAPX','CBL','CBP','CC','CCGAME','CCN','CCP','CCS','CD','CDF','CFC','CLASS','CLIPS','CLS','CLW','COB','COD','CONFIG','CP','CP','CPP','CS','CSI','CSI','CSN','CSP','CSPROJ','CSX','CTL','CTP','CTXT','CU','CVSRC','CXP','CXX','D','DBA','DBA','DBML','DBO','DBPRO','DBPROJ','DCP','DCPROJ','DCU','DCUIL','DEC','DEF','DEVICEIDS','DEX','DF1','DFM','DGML','DGSL','DIFF','DM1','DMD','DOB','DOX','DPK','DPKW','DPL','DPR','DPROJ','DSGM','DSP','DTD','EDML','EDMX','ENT','ENTITLEMENTS','EQL','ERB','ERL','EX','EXP','EXW','F','F90','FBP','FBZ7','FGL','FLA','FOR','FORTH','FPM','FRAMEWORK','FRX','FS','FSI','FSPROJ','FSPROJ','FSSCRIPT','FSX','FTL','FTN','FXC','FXCPROJ','FXL','FXML','FXPL','GAMEPROJ','GCH','GED','GEM','GEMSPEC','GFAR','GITATTRIBUTES','GITIGNORE','GLD','GM6','GM81','GMD','GMK','GMX','GORM','GREENFOOT','GROOVY','GROUPPROJ','GS','GS3','GSPROJ','GSZIP','H','HAL','HAML','HAS','HBS','HH','HPF','HPP','HS','HXX','I','ICONSET','IDB','IDL','IDT','ILK','IML','INC','INL','INO','IPCH','IPR','IPR','ISE','ISM','IST','IWB','IWS','JAVA','JCP','JIC','JPR','JPX','JSFL','JSPF','KDEVELOP','KDEVPRJ','KPL','KV','L','LBI','LBS','LDS','LGO','LHS','LICENSES','LICX','LISP','LIT','LIVECODE','LNT','LPROJ','LSPROJ','LTB','LUA','LUC','LUCIDSNIPPET','LXSPROJ','M','M','M4','MAGIK','MAK','MARKDOWN','MCP','MD','MDZIP','MER','MF','MFA','MK','ML','MM','MOD','MOM','MPR','MRT','MSHA','MSHC','MSHI','MSL','MSP','MSS','MV','MXML','MYAPP','NBC','NCB','NED','NEKO','NFM','NIB','NK','NLS','NQC','NSH','NSI','NUPKG','NUSPEC','NVV','NW','NXC','O','OAT','OCA','OCTEST','OCX','ODL','OMO','OWL','P','P3D','PAS','PAS','PATCH','PAW','PB','PBG','PBJ','PBK','PBXBTREE','PBXPROJ','PBXUSER','PCH','PCP','PDE','PDM','PH','PIKA','PJX','PKGDEF','PKGUNDEF','PL','PL','PL1','PLAYGROUND','PLC','PLI','PM','PO','POD','POT','PPC','PRG','PRI','PRI','PRO','PROTO','PSC','PSM1','PTL','PWN','PXD','PY','PYD','PYW','PYX','QPR','R','R','R','RAV','RB','RBC','RBP','RBW','RC','RC2','RDLC','REFRESH','RES','RES','RESJSON','RESOURCES','RESW','RESX','REX','REXX','RISE','RKT','RNC','RODL','RPY','RSRC','RSS','RUL','S','S19','SAS','SB','SB2','SBPROJ','SC','SCALA','SCC','SCRIPTSUITE','SCRIPTTERMINOLOGY','SDEF','SH','SLN','SLOGO','SLTNG','SMA','SMALI','SNIPPET','SO','SPEC','SQLPROJ','SRC','SRC.RPM','SS','SSC','SSI','STORYBOARD','SUD','SUO','SUP','SVN-BASE','SWD','SWIFT','SYM','T','TARGETS','TCL','TDS','TESTRUNCONFIG','TESTSETTINGS','TEXTFACTORY','TK','TLD','TLH','TLI','TMLANGUAGE','TMPROJ','TNS','TPU','TRX','TT','TU','TUR','TWIG','UI','UML','V','V','V11.SUO','V12.SUO','VB','VBG','VBP','VBPROJ','VBX','VBZ','VC','VCP','VCPROJ','VCXPROJ','VDM','VDP','VDPROJ','VGC','VHD','VM','VSMACROS','VSMDI','VSMPROJ','VSP','VSPS','VSPSCC','VSPX','VSSSCC','VSZ','VTM','VTML','VTV','W','W32','WDGT','WDGTPROJ','WDL','WDP','WDW','WIQ','WIXLIB','WIXMSP','WIXMST','WIXOBJ','WIXOUT','WIXPDB','WIXPROJ','WORKSPACE','WPW','WSC','WSP','WXI','WXL','WXS','XAML','XAMLX','XAP','XCAPPDATA','XCARCHIVE','XCCONFIG','XCDATAMODELD','XCODEPROJ','XCSNAPSHOTS','XCWORKSPACE','XIB','XOJO_BINARY_PROJECT','XOJO_MENU','XOJO_PROJECT','XOJO_XML_PROJECT','XOML','XPP','XQ','XQL','XQM','XQUERY','XSD','Y','YAML','YML','YMP','YPR']



    );

    const TYPE_FOLDER = "FOLDER";



    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }



    /**
     * @param string $data
     */
    public function actionIndex()
    {


//         This is a shortway to set allowed extensions. You can set your own extensions in an array.
        $this->allowedFormats = array_merge(
            self::$FILES_TYPES['IMAGE'],
            self::$FILES_TYPES['SPREADSHEET'],
            self::$FILES_TYPES['PDF'],
            self::$FILES_TYPES['TEXT'],
            self::$FILES_TYPES['PRESENTATION'],
            self::$FILES_TYPES['VECTOR'],
            self::$FILES_TYPES['DB'],
            self::$FILES_TYPES['COMPRESSED'],
            self::$FILES_TYPES['AUDIO']

        );

        $this->allowedFormats=array_map('strtolower', $this->allowedFormats);

//        @fixme: Convert to actions instead using full requests
        $data=$_REQUEST;

        $this->rootPath=realpath($this->rootFolder);

        if (!$this->checkFolderPermissions($this->thumbnailDir) || !$this->checkFolderPermissions($this->initialDir)) {
            $this->error = "Check folders permissions";
//            $this->ajaxResponse();
        }
        if(!is_dir($this->initialDir))
        {
            $this->error="Folder does not exist";
        }

        if (isset($data['folder']) && trim($data['folder']) != "") {
            $this->dir = $this->validateFolder($data['folder'] . "/");
        } else {
            $this->dir = $this->initialDir;
        }

        $action = "";
        if (isset($data['action'])) {
            $action = $data['action'];
        }

        if ($this->checkFileTypes($data)) {

            switch ($action) {
                case "list":
                    $this->ajaxResponse($this->getFiles());
                    break;
                case "delete":
                    if ($this->delete($data['item'])) {
                        $this->ajaxResponse($this->getFiles());
                    }
                    break;
                case "upload":

                    $this->handleUpload($this->dir);

                    break;
                case "fileinfo":
                    $this->getFileInfo($data['file']);
                    break;
                case "makedir":
                    $this->makeDir($data['item']);
                    break;
                default:
                case "tree":

                    $this->getTree();
                    break;
            }

        }


    }

    private function checkFileTypes($data)
    {
        if (isset($data['fileTypes']) && is_array($data['fileTypes']) && count($data['fileTypes']) > 0) {
            $imgToo = array_search('image', $data['fileTypes']);

            if ($imgToo > -1) {
                unset($data['fileTypes'][$imgToo]);
                $data['fileTypes'] = array_merge($data['fileTypes'], $this->imageFormats);
            }

            if($this->allowedFormats)
            {
                if ($this->allowedFormats = array_intersect($data['fileTypes'], $this->allowedFormats)) {
                    return true;
                }
            }else
            {
                $this->allowedFormats=$data['fileTypes'];
            }

        }

        return true;
    }

    /**
     * Checks if a folder is writable
     * @param $folder
     * @return bool
     */
    private function checkFolderPermissions($folder)
    {
        if(in_array(strtolower(basename($folder, PATHINFO_BASENAME)), $this->forbiddenFolders))
        {
            $this->error="Forbidden access";
            $this->ajaxResponse();
        }
        return is_dir($folder) && (is_writable($folder));

    }

    /**
     *  Start generating the tree structure for folder. It creates the first branch, then calls to _getTree
     */
    private function getTree()
    {

        $this->foldersCounter = 0;
        $AllFiles = array();
//        $this->tree=$this->_getTree(substr($this->initialDir,0,-1),array(),0);
        if(!$this->allowedFormats)
        {
            $filesInFolder = glob($this->initialDir . "{*.*}", GLOB_BRACE);

        }else
        {
            $filesInFolder = glob($this->initialDir . "{*." . implode(",*.", $this->allowedFormats) . "}", GLOB_BRACE);
        }
        $this->processFolder($filesInFolder, $AllFiles);

        $this->tree[] = array(
            'name' => pathinfo($this->initialDir, PATHINFO_BASENAME),
//            todo: Repair _getTree
            'subfolders' => $this->_getTree($this->initialDir, substr($this->initialDir, -1), 0),
//            'level' => array(0),
            'depth' => 0,
            'fullDir' => pathinfo($this->initialDir, PATHINFO_BASENAME),
            'filesCount' => count($filesInFolder),
            'files' => $AllFiles
        );
        $this->ajaxResponse(array("data" => $this->tree));
    }

    /**
     *  Recursive function for create branches of tree.
     * @param $folders - Folders inside the main branch
     * @param $folder - Folder to explore
     * @param $depth - Depth level counter
     * @return array - Returns branches of the selected folders
     */
    private function _getTree($folders, $level, $depth)
    {

        if ($this->foldersCounter >= $this->maxFolderLimit) {
//            $this->error = "Max number of folders reached";
//            $this->ajaxResponse(array());
            return [];
        }
        $this->foldersCounter++;
        if ($depth) {
            $tree = array();
        };

        $theFolders = glob($folders . "*", GLOB_ONLYDIR);
//        die(var_dump($theFolders));
        foreach ($theFolders as $key => $folder) {


            if (in_array(strtolower(basename($folder, PATHINFO_BASENAME)), $this->forbiddenFolders))
                continue;

            $AllFiles = array();

            if(!$this->allowedFormats)
            {
                $filesInFolder = glob($folder . "/{*.*}", GLOB_BRACE);
            }else
            {
                $filesInFolder = glob($folder . "/{*." . implode(",*.", $this->allowedFormats) . "}", GLOB_BRACE);
            }

//
//            die($folders ."{*.".implode(",*.",$this->allowedFormats)."}");

//            $this->processFolder($filesInFolder,$AllFiles);

            if (is_dir($folder)) {

                $this->processFolder($filesInFolder, $AllFiles);
                $depth++;
//                $level[] = $key;
                $tree[] = array(
                    'name' => pathinfo($folder, PATHINFO_BASENAME),
                    'subfolders' => $this->_getTree($folder . "/", $folder, $depth),
//                    'level' => $level,
                    'fullDir' => $folder,
                    'depth' => $depth,
                    'filesCount' => count($filesInFolder),
                    'files' => $AllFiles
                );
//                array_pop($level);
                $folder = substr($folder, 0, strrpos($folder, "/"));
            }
        }

        return $tree;

    }

    /**
     * Return all the files in the current folder
     * @return array
     */
    private function getFiles()
    {

        $data = array();
        $data['pics'] = array();
        $data['folders'] = array();

        if (!is_dir($this->dir)) {

        }
        $files = glob($this->dir . "*");

        if($files===false)
            $files=[];

        usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
//            $this->info.=" glob al dir: ".$this->dir." NIVEL: ".$this->level;
        $this->processFolder($files, $data);


        $data['currentFolder'] = pathinfo($this->dir, PATHINFO_BASENAME);
        $data['currentFolderFull'] = $this->dir;

        usort($data['folders'],function($a,$b){
            return strcmp($a["dirName"], $b["dirName"]);
        });
        if(isset($data['others'])) {
            ksort($data['others']);
        }
//        $data['error']=$this->error;
        $data['info'] = $this->info;
        return $data;

    }

    /**
     * Returns the file type
     * @param $name
     * @return int|string
     */
    public static function getFileType($name)
    {
        foreach (self::$FILES_TYPES as $type => $exts) {

            if (in_array(strtolower($name), $exts)) {
                return $type;
            }

        }

    }

    /**
     *  Sorts and group all files in a folder and store it inside $data. Check that $data is passed by reference
     * @param $files - Files array to sort
     * @param $data - Folder value passed by reference
     */
    private function processFolder($files, &$data)
    {
        foreach ($files as $key => $file) {
//                If is a dir
            if (is_dir($file)) {
                if (in_array(strtolower(basename($file, PATHINFO_BASENAME)), $this->forbiddenFolders))
                    continue;

//                    $data['folders'][] = str_replace($this->dir, "", $file);
                $data['folders'][] = array(
                    "fullDir" => $file,
                    "dirName" => str_replace($this->dir, "", $file),
                    "isEmpty" => $this->is_dir_empty($file),
                    "fileType" => self::TYPE_FOLDER
                );
//                    If is an image1
            } else if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $this->allowedFormats?array_intersect($this->imageFormats, $this->allowedFormats):$this->imageFormats)) {

                $this->createThumbnail($file);
                $data['pics'][] = array(
                    "fullDir" => $file,
                    "fileName" => pathinfo($file, PATHINFO_BASENAME),
                    "fileType" => self::getFileType(pathinfo($file, PATHINFO_EXTENSION))

                );


            } else if ($this->allowedFormats?in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array_diff($this->allowedFormats, $this->imageFormats)):true) {

                $data['others'][strtolower(pathinfo($file, PATHINFO_EXTENSION))][] = array(
                    "fullDir" => $file,
                    "fileName" => pathinfo($file, PATHINFO_BASENAME),
//                    "fileNameFormat"=>str_replace(array("_","-",".")," ",pathinfo($file, PATHINFO_BASENAME)),
                    "fileType" => self::getFileType(pathinfo($file, PATHINFO_EXTENSION))
                );
            }
        }

    }

    /**
     * Handles the file upload. It also checks white list filetypes.
     * @param $uploadDir
     */
    private function handleUpload($uploadDir)
    {
        $messages = array();
        if ($this->allowUpload) {
            if (isset($_FILES['file']['tmp_name'])) {
                // Number of uploaded files
                $num_files = count($_FILES['file']['tmp_name']);

                /** loop through the array of files ***/
                for ($i = 0; $i < $num_files; $i++) {
                    if (isset($_FILES['file']['error'][$i])) {
//                        $messages[] = $_FILES['file']['error'][$i];
                    }
                    // check if there is a file in the array
                    if (!is_uploaded_file($_FILES['file']['tmp_name'][$i])) {
                        $messages[] = 'No file uploaded';
                    } else if ($this->allowedFormats?in_array(strtolower(pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION)), $this->allowedFormats):true) {
                        // copy the file to the specified dir
                        $dest = $uploadDir . '/' . $_FILES['file']['name'][$i];
                        $newFile = $this->getNewFilename($dest);
                        if (@copy($_FILES['file']['tmp_name'][$i], $newFile)) {
                            if (in_array(strtolower(pathinfo($newFile, PATHINFO_EXTENSION)), self::$FILES_TYPES['IMAGE']))
                                $this->createThumbnail($newFile);
                        } else {
                            $messages[] = 'Uploading ' . $_FILES['file']['name'][$i] . ' Failed';
                        }
                    } else {
                        $messages[] = "Invalid format";
                    }
                }
                $this->error = $messages;
            }
        } else {
            $this->error = "Upload not allowed";
        }

        $this->ajaxResponse();
    }

    /**
     *  Checks whether a file exists and generates a new name if necessary. If file name exists it appends a number. I.e.: file.jpg, file_1.jpg,file_2.jpg
     * @param $filename
     * @param int $counter
     * @return mixed
     */
    private function getNewFileName($filename, $counter = 1)
    {
        if (file_exists($filename)) {

            $pieces = pathinfo($filename);
            $name = $pieces['filename'];
            if ($counter > 1) {
                $name = substr($name, 0, ($counter >= 11 ? -3 : -2));
            }
            $newFile = $pieces['dirname'] . "/" . $name . "_" . $counter++ . "." . $pieces['extension'];
            return $this->getNewFileName($newFile, $counter);

        } else {
            return $filename;
        }
    }

    /**
     * Checks if a folder is empty
     * @param $dir
     * @return bool|null
     */
    private function is_dir_empty($dir)
    {
        if (!is_readable($dir)) return false;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return false;
            }
        }
        return true;
    }

    /**
     * Function to delete a file
     * Warning,we highly recommend ensure the access security to this methods and the restrictions of the folders
     */
    private function delete($file)
    {
        if (!$this->validateFolder($file)) ;
        $exName = explode(".", $file);

        if ($this->allowedFormats===false || in_array(strtolower(end($exName)), $this->allowedFormats) || is_dir($file)) {
            if (is_dir($file) && $this->allowDeleteDirs) {
                //todo: Use try/catch instead @
                if (@!rmdir($file)) {
                    $this->info .= "-- Direct:" . $file;
                    $this->error .= 'Error deleting directory';
                    return true;
                }
            }

            if (file_exists($file)) {
                //todo: Use try/catch instead @
                if (@!unlink($file)) {
                    $this->error .= "Error deleting file";
                    return true;
                } else {
                    $this->deleteThumbnail($file);
                }
            }

//            $this->error.="Operation not permitted";

        } else {
//            $this->info.="---Archivo; ".$file;
            $this->error .= "Operation not allowed. Delete " . $file.(in_array(strtolower(end($exName)), $this->allowedFormats)?"staba en lor permitiod":"no estaba".strtolower(end($exName)));
            return true;
        }
        return true;
    }

    /**
     * Ends execution of php and returns a JSON object with data and errors.
     * @param $data
     */
    private function ajaxResponse($data = array())
    {
        $data["error"] = $this->error;
        die(json_encode($data));
    }

    /**
     * @param $folder
     * @return mixed
     */
    private function validateFolder($folder)
    {

        if($fullPath=realpath($this->initialDir))
        {
            if($fullFolderPath=realpath($folder))
            {
                if(strpos($fullFolderPath,$fullPath)!==false)
                {
//                    die($fullPath." --- ".$fullFolderPath." *** ".strpos($fullPath,$fullFolderPath));
                    return $folder;

                }
            }

        }return $this->initialDir;
    }

    /**
     * @deprecated: No more using key based navigation
     * Returns the folder selected following the order in the array
     * @param $level
     * @return string
     */
    private function getLevel()
    {

        $levels = explode(",", $this->level);

        if (count($levels) <= 1) {
//            $this->info.="Se va por aqui: ".$this->level;
            return $this->initialDir;
        }


        $this->dir = $this->initialDir;

        array_shift($levels);
        foreach ($levels as $folderKey) {

            $folders = glob($this->dir . "*", GLOB_ONLYDIR);

            if (is_dir($folders[$folderKey])) {
//                $this->info.=$folders[$folderKey];
                $this->dir = $folders[$folderKey] . "/";
//                return true;

            } else {
                $this->error .= "No es un dir:" . $folders[$folderKey];

//                return true;
            }

        }

        return true;

    }

    /**
     * Deletes the thumbnail of a specific file
     * @param $filename - The file
     */
    public function deleteThumbnail($filename)
    {
        $pi = pathinfo($filename);
        if (file_exists($this->thumbnailDir . $filename)) {
            @unlink($this->thumbnailDir . $filename);

        }
    }

    /**
     *  Generates the thumbnail for a specific file
     * @param $filename
     */
    public function createThumbnail($filename)
    {
        $errors = 0;
        $name = pathinfo($filename, PATHINFO_BASENAME);
        $filefolder = pathinfo($filename, PATHINFO_DIRNAME);

        $folder=str_ireplace($this->rootPath,"",realpath($filefolder));


//        Get real extension
        $pieces = explode(".", $filename);
        $ext = strtolower(end($pieces));
        $dest = $this->thumbnailDir . $folder . "/" . $name;


        if (file_exists($dest)) {
            return;
        }

        if (!is_dir($this->thumbnailDir . $folder)) {
            mkdir($this->thumbnailDir . $folder, 0755, true);
        }

        if ($ext == "jpg" || $ext == "jpeg") {
            if (!$im = @imagecreatefromjpeg($filename)) {
                $errors++;
            };
        } else if ($ext == "gif") {
            if (!$im = @imagecreatefromgif($filename)) {
                $errors++;
            };
        } else if ($ext == "png") {

            if (!$im = @imagecreatefrompng($filename)) {
                $errors++;
            };
            imagealphablending($im, false);
            imagesavealpha($im, true);
        }

        if ($errors)
            return;


        if (!get_resource_type($im) == "gd")
            return;


        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = $this->thumbnailWidth;
        $ny = floor($oy * ($this->thumbnailWidth / $ox));

        if($nm = @imagecreatetruecolor($nx, $ny))
        {
            imagealphablending($nm, false);
            imagesavealpha($nm, true);

            if ($this->resampledThumbail)
                imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
            imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);

            if ($ext == "jpg" || $ext == "jpeg") {
                imagejpeg($nm, $dest);

            } else if ($ext == "gif") {
                imagegif($nm, $dest);

            } else if ($ext == "png") {
                imagepng($nm, $dest);

            }
        }

    }

    /**
     * Creates a new directory
     * @param $name
     */
    private function makeDir($name)
    {


        $newName = $this->slug($name);

        if ($newName != "" && !is_dir($this->dir . $newName)) {
            if (!@mkdir($this->dir . $newName)) {
                $this->error .= "Error creating the new directory";
            }
        }
        $this->ajaxResponse($this->getFiles());


    }

    /**
     * Returns information about a file. Used in preview.
     * @param $file
     */
    private function getFileInfo($file)
    {
        $result = [];
        $ext=pathinfo($file, PATHINFO_EXTENSION);
        $name=pathinfo($file,PATHINFO_FILENAME);
        if ($this->allowedFormats?in_array(strtolower($ext), $this->allowedFormats):true) {
            if ($info = stat($file) ) {
                $finfo=finfo_open(FILEINFO_MIME_TYPE);
                $result['name'] = $name;
                $result['filesize'] = $this->humanFileSize($info[7]);
                $result['date'] = date($this->dateFormat, $info[9]);
                $result['ext'] = strtoupper($ext);
                $result['type'] = finfo_file($finfo,$file);

                if(in_array(strtolower($ext), $this->allowedFormats?array_intersect($this->imageFormats, $this->allowedFormats):$this->imageFormats))
                {
                    $size=getimagesize($file);
                    $result['width']=$size[0]."px";
                    $result['height']=$size[1]."px";

                }

            } else {
                $result['error']="Cannot read file info";
            }
        } else {
            $result['error'] = "Not allowed";
        }

        die(json_encode($result));

    }

    /**
     * Converts bytes to human readable size
     * @param $size
     * @param string $unit
     * @return string
     */
    public function humanFileSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 30) || $unit == "GB")
            return number_format($size / (1 << 30), 2) . "GB";
        if ((!$unit && $size >= 1 << 20) || $unit == "MB")
            return number_format($size / (1 << 20), 2) . "MB";
        if ((!$unit && $size >= 1 << 10) || $unit == "KB")
            return number_format($size / (1 << 10), 2) . "KB";
        return number_format($size) . " bytes";
    }


    /**
     * Replaces all accent and strange characters from string
     * @param $title - The original string
     * @param string $separator - The character to use as replacement
     * @return string - The string with characters replaced
     */
    public function slug($title, $separator = '-')
    {

        $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
        $title = strtr($title, $unwanted_array);
        // convert String to Utf-8 Ascii
        $title = iconv(mb_detect_encoding($title, mb_detect_order(), true), "UTF-8", $title);

        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

        return trim($title, $separator);
    }
}
