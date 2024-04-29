(()=>{"use strict";const e=window.React,t=window.wp.i18n,l=window.wp.blocks,n=window.wp.blockEditor,a=window.wp.components;(0,l.registerBlockType)("my-plugin/my-custom-block",{title:(0,t.__)("My Custom Block","text-domain"),icon:"smiley",category:"common",attributes:{content:{type:"string",source:"html",selector:"p"},pdfUrl:{type:"string",default:""},thumbnailUrl:{type:"string",default:""},customText:{type:"string",source:"text",selector:".custom-text",default:"Default custom text"}},edit:l=>{const{attributes:{content:o,pdfUrl:c,thumbnailUrl:s,customText:r},setAttributes:m,className:i}=l;return(0,e.createElement)("div",{className:"user-download-block"},(0,e.createElement)(n.RichText,{tagName:"p",className:i,onChange:e=>m({content:e}),value:o,placeholder:(0,t.__)("Write your custom message","text-domain")}),(0,e.createElement)(n.MediaUploadCheck,null,(0,e.createElement)(n.MediaUpload,{onSelect:e=>{const t=e.url;let l="";l=e.sizes&&e.sizes.thumbnail?e.sizes.thumbnail.url:e.icon||"",m({pdfUrl:t,thumbnailUrl:l,customText:r})},allowedTypes:["application/pdf"],value:c,render:({open:t})=>(0,e.createElement)(a.Button,{onClick:t},c?"Change PDF":"Upload PDF")})),(0,e.createElement)(n.InspectorControls,null,(0,e.createElement)(a.PanelBody,{title:"Custom Text Settings"},(0,e.createElement)(a.TextControl,{label:"Custom Text",value:r,onChange:e=>{m({customText:e})}}))),c&&(0,e.createElement)("a",{href:c},"Download PDF"),s&&(0,e.createElement)("img",{src:s,alt:"PDF Thumbnail"}))},save:({attributes:t})=>{const{content:l,pdfUrl:a,thumbnailUrl:o,customText:c}=t;return(0,e.createElement)("div",{className:"user-download-block"},(0,e.createElement)(n.RichText.Content,{tagName:"p",value:l}),(0,e.createElement)(n.RichText.Content,{tagName:"p",className:"custom-text",value:c}),a&&(0,e.createElement)("a",{href:a},"Download PDF"),o&&(0,e.createElement)("img",{src:o,alt:"PDF Thumbnail"}))}})})();