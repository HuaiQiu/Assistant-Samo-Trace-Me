/** 
	 * @function
	 * @memberof Assist
	 * @name ViewTrace
	 * @desc visualisation de la trace
**/		
		
Assist.ViewTrace = {
	addObselVisu : function (obsel,divId)
  	{
  		url= $("#URLTRACE").val()+$("#TraceName").val();
  		if (Assist.Model.getModelSpecific(url)!=undefined)
      	{
      			models = Assist.Model.getModelSpecific(url) ;
      	}
      //	else
  				//models = JSON.parse (localStorage["Assit.Model"]);
  				valT = getNameT(obsel["@type"]);
  
 		var modelT = Assist.Model.getModelType (valT,models);
		if  (modelT != undefined)
		{
			   var p =  document.createElement("p");// manière de représenter
			   p.setAttribute ("id",obsel["@id"]);
			   var att =  document.createElement(modelT["style"]);

	   	   //for selected language choose the right text to display
	        var typetxt;
	        if (modelT["text"] !=="")
	        {
	        	typetxt= modelT["text"]
	        }
	   		else if ( localStorage["LangChange"] == "EN")
	   		{
	   			typetxt = modelT["text-EN"];
     		}
	   		else if ( localStorage["LangChange"] == "DE")
	   		{
	   			typetxt = modelT["text-DE"];
			}
	   		else if ( localStorage["LangChange"] == "FR")
	   	    {
	   			typetxt = modelT["text-FR"];
	   		}

	   		

	   if (typetxt !== "")
	   {
	       att.appendChild (document.createTextNode("M-Type :"+ typetxt+" "));
	   }
	   else 
	   {
	       att.appendChild (document.createTextNode("M-Type :"+valT+" "));
	   }
	       p.appendChild (att);
	   if (modelT.attribut.length !== 0)
	   {
	        for (j in obsel) 
		        {    
		           valT=getName(j);
		            var modelA = Assist.Model.getModelAttribute (valT,modelT)
		            if  (modelA != undefined) 
		              {
		              //createDomAttribut 
		               var att =  document.createElement(modelA["style"]);

		               //change attribute language on change
					   var atttext;
					   		if (modelT["text"] !=="")
	        					{
	        						atttext= modelA["text"];
	       						}

					   		if ( localStorage["LangChange"] == "EN")
					   		{
					   			atttext = modelA["text-EN"];
					   		}
					   		else if ( localStorage["LangChange"] == "DE")
					   		{
					   			atttext = modelA["text-DE"];
					   		}
					   		else
					   	    {
					   			atttext = modelA["text-FR"];
					   		}
		                  
		                if (atttext !== "")
		                {
		                    if (modelA["style"] == "lien")
		                    {
		                     var a = document.createElement("a");
		                     a.setAttribute("href",obsel[j]);
		                     a.setAttribute("target","_blank");
		                     a.appendChild (document.createTextNode(obsel[j]));
		                     att.appendChild (document.createTextNode(" "+atttext+" :"));
		                     att.appendChild(a);
		                     
		                    }

		                    else
		                  att.appendChild (document.createTextNode(" "+atttext+" :"+obsel[j]));
		                }
		                else 
		                {
		                
	  
		                if (modelA["style"] == "lien")
		                    {var a = document.createElement("a");
		                     a.setAttribute("href",obsel[j]);
		                     a.setAttribute("target","_blank");
		                     a.appendChild (document.createTextNode(obsel[j]));
		                     att.appendChild (document.createTextNode(" "+valT+" :"));
		                     att.appendChild(a);
		                     
		                    }
		                    else if (atttext == undefined)
		                    {
		                    	att.appendChild (document.createTextNode(" "+valT+" :"+obsel[j]));
		                
		                    }
		                 else 
		                att.appendChild (document.createTextNode(" "+valT+" :"+obsel[j]));
		                
		                
		                }
		                p.appendChild (att);
		              }
		            
		            
	            }
	    }
		document.getElementById(divId).appendChild(p);
}
else 
{
    var p =  document.createElement("p");
     p.setAttribute("id",obsel["@id"]);
			att=document.createElement("p");
			att.appendChild (document.createTextNode("M-Type :"+valT+" "));
			p.appendChild (att);
			document.getElementById(divId).appendChild(p);
}

},
    generateVisu : function (url,obsels)	
{
      if (Assist.Model.getModelSpecific(url)!=undefined)
      models = Assist.Model.getModelSpecific(url) ;
      else
      models = JSON.parse (localStorage["Assit.Model"]);
      for ( var i = 0; i < obsels.length; i++) 
     {
      
      if ((obsels[i]["m:Ouverture_Page/hasDocument_Title"])&&((obsels[i]["m:Ouverture_Page/hasDocument_Title"]=="visualisation")||(obsels[i]["m:Ouverture_Page/hasDocument_Title"]=="Visualisation")||(obsels[i]["m:Ouverture_Page/hasDocument_Title"]==="")))
      {}
	  else 
	  { Assist.ViewTrace.addObselVisu (obsels[i]);}
	 }

}
}

