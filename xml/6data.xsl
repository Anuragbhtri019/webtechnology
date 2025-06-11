<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>Book List and Student List</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1 { font-weight: bold; font-size: 24px; }
                    h2 { font-weight: bold; font-size: 20px; }
                    table { border: 1.5px solid black; width: 50%; margin-bottom: 20px; table-layout: absolute; }
                    th, td { border: 1.5px solid black; padding: 8px; text-align: left; width :35%; }
                    th { background-color: #f2f2f2; }
                    ul { list-style-type: disc; margin-left: 20px; }
                    p.and { font-weight: bold; margin: 10px 0; }
                </style>
            </head>
            <body>
                <h1>Book List</h1>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Edition</th>
                        <th>Price</th>
                    </tr>
                    <xsl:for-each select="library/books/book">
                        <tr>
                            <td><xsl:value-of select="title"/></td>
                            <td><xsl:value-of select="author"/></td>
                            <td><xsl:value-of select="publisher"/></td>
                            <td><xsl:value-of select="edition"/></td>
                            <td><xsl:value-of select="price"/></td>
                        </tr>
                    </xsl:for-each>
                </table>

                <p class="and">And,</p>

                <h2>List of class students</h2>
                <ul>
                    <xsl:for-each select="library/students/student">
                        <li>
                            Name: <xsl:value-of select="name"/>, 
                            Address: <xsl:value-of select="address"/>, 
                            Roll no: <xsl:value-of select="roll_no"/>, 
                            Batch: <xsl:value-of select="batch"/>
                        </li>
                    </xsl:for-each>
                </ul>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>