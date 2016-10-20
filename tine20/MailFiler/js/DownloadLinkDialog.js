/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2014 Metaways Infosystems GmbH (http://www.metaways.de)
 * 
 * TODO         maybe we don't need this
 */
Ext.ns('Tine.MailFiler');

/**
 * @namespace   Tine.MailFiler
 * @class       Tine.MailFiler.DownloadLinkDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Sieve Filter Dialog</p>
 * <p>This dialog is for editing sieve filters (rules).</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * 
 * @param       {Object} config
 * @constructor
 * Create a new RulesDialog
 */
Tine.MailFiler.DownloadLinkDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {

    /**
     * @cfg {Tine.Felamimail.Model.Account}
     * 
     * TODO use node record?
     */
    node: null,

    /**
     * @private
     */
    windowNamePrefix: 'DownloadLinkWindow_',
    appName: 'MailFiler',
//    loadRecord: false,
    mode: 'local',
    tbarItems: [],
    evalGrants: false,
    
    //private
    initComponent: function(){
        Tine.MailFiler.DownloadLinkDialog.superclass.initComponent.call(this);
        
        this.i18nRecordName = this.app.i18n._('Node Download Links');
    },
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * 
     * @private
     */
    updateToolbars: Ext.emptyFn,
    
    /**
     * init record to edit
     * -> we don't have a real record here
     */
    initRecord: function() {
//        this.onRecordLoad();
    },
    
    /**
     * executed after record got updated from proxy
     * -> we don't have a real record here
     * 
     * @private
     */
    onRecordLoad: function() {
    },
        
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * 
     * @return {Object}
     * @private
     * 
     */
    getFormItems: function() {
        this.linkGrid = new Tine.MailFiler.DownloadLinkGridPanel({
            //account: this.account
        });
        
        return [this.linkGrid];
    }
});

/**
 * DownloadLink Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.MailFiler.DownloadLinkDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 400,
        name: Tine.MailFiler.DownloadLinkDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.MailFiler.DownloadLinkDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
