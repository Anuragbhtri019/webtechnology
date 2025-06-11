<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!-- Basic HTML setup -->
    <xsl:template match="/">
        <html>
            <body>
                <xsl:apply-templates select="topics"/>
            </body>
        </html>
    </xsl:template>

    <!-- Topics box -->
    <xsl:template match="topics">
        <div style="font-family: Arial; text-align: center;  padding: 10px; margin: 20px auto; border: 2px solid #87ceeb;">
            <xsl:apply-templates select="header"/>
            <xsl:apply-templates select="category"/>
        </div>
    </xsl:template>

    <!-- Header -->
    <xsl:template match="header">
        <div style="background: #008000; color: #fff; font-size: 1.2em; font-weight: bold; padding: 5px; margin-bottom: 15px;">
            <xsl:value-of select="."/>
        </div>
    </xsl:template>

    <!-- Category -->
    <xsl:template match="category">
        <div style="margin: 15px 0; padding: 10px; background: #f9f9f9; border-radius: 5px;">
            <xsl:apply-templates select="categoryName"/>
            <xsl:apply-templates select="item"/>
        </div>
    </xsl:template>

    <!-- Category name -->
    <xsl:template match="categoryName">
        <div style="font-size: 1.1em; font-weight: bold; color: #00ff00;">
            <xsl:value-of select="."/>
        </div>
    </xsl:template>

    <!-- Items with minimal color logic -->
    <xsl:template match="item">
        <div style="margin-left: 20px; margin-bottom: 3px;">
            <span>
                <xsl:attribute name="style">
                    <xsl:choose>
                        <xsl:when test="position() = 1">color:rgb(255, 0, 0);</xsl:when> <!-- Red for 1st -->
                        <xsl:when test="position() = 2">color:rgb(254, 0, 254);</xsl:when> <!-- Magenta for 2nd -->
                        <xsl:when test="position() = 3">color:rgb(22, 206, 206);</xsl:when> <!-- Cyan for 3rd -->
                        <xsl:when test="position() = 4">color:rgb(207, 210, 11);</xsl:when> <!-- Yellow for 4th -->
                        <xsl:otherwise>color: #000;</xsl:otherwise> <!-- Black default -->
                    </xsl:choose>
                </xsl:attribute>
                <xsl:value-of select="."/>
            </span>
        </div>
    </xsl:template>

</xsl:stylesheet>