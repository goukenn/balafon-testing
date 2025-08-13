const sketch = require("sketch")
const { Settings, UI } = sketch
const __NAME__ = "balafon"
const document = sketch.getSelectedDocument();
const { selectedLayers }= document;
/**
 * 
 * @param {*} context 
 */
function fitW(context){ 
    selectedLayers.forEach((layer, i)=>{
     const { width, height } = layer.getParentArtboard().frame;
     let _nframe = layer.sketchObject.frame()
     _nframe.setX(0)
     _nframe.setWidth(width)
 });
 }
 /**
  * 
  * @param {*} context 
  */
 function fitH(context){ 
    selectedLayers.forEach((layer, i)=>{
     const { width, height } = layer.getParentArtboard().frame;
     let _nframe = layer.sketchObject.frame()
     _nframe.setY(0)
     _nframe.setHeight(height)
 });
 }
/**
 * 
 * @param {*} context 
 */
 function fit(context){ 
    /**
     * 
     */
    selectedLayers.forEach(
        /**
         * 
         * @param {*} layer  
         */
        (layer)=>{
     const { width, height } = layer.getParentArtboard().frame;
     let _nframe = layer.sketchObject.frame()
     _nframe.setX(0);
     _nframe.setY(0);
     _nframe.setWidth(width);
     _nframe.setHeight(height);
 });
 }