<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Oceans Data</title>
                <style type="text/css">
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        background-color: #f9f9f9; /* Light background for the page */
                        
                        border-radius: 3px;
                        border-color: #d9e8fb;
                    }
                    .oceans {
                        max-width: 500px;
                       
                        margin: 0 auto;
                        
                        padding: 15px;
                      
                        background-color: #ffffff; /* White background for the content */
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for better appearance */
                    }
                    .oceans h1 {
                        max-width:200px;
                        font-size: 1.8em; /* Larger font size for the title */
                        font-weight: bold;
                        margin: 0;
                        color: #000;
                        background-color: #d9e8fb; /* Light blue background for the title */
                        padding: 15px 10px;
                        text-align: left; /* Left-align the title */
                        border-radius: 5px 5px 0 0; /* Rounded top corners for the title background */
                    }
                    .ocean {
                        margin-bottom: 20px;
                        
                    }
                    .ocean h2 {
                        font-size: 1.2em; /* Slightly larger font size for ocean names */
                        font-weight: bold;
                        margin: 10px 0 5px 0;
                       
                        color: #000;
                    }
                    .ocean p {
                        margin: 0 0 5px 20px;
                        font-size: 1em;
                        color: #000;
                        line-height: 1.5; /* Increased line spacing for better readability */
                    }
                    .ocean p:last-child {
                        margin-bottom: 0;
                    }
                </style>
            </head>
            <body>
                <div class="oceans">
                    <h1>Oceans</h1>
                    <xsl:apply-templates select="Oceans/Ocean"/>
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="Ocean">
        <div class="ocean">
            <h2><xsl:value-of select="Name"/></h2>
            <p>Area: <xsl:value-of select="format-number(Area div 1000, '##.000')"/> million km²</p>
            <p>Mean depth: <xsl:value-of select="format-number(MeanDepth, '#,###')"/> m</p>
        </div>
    </xsl:template>

</xsl:stylesheet>